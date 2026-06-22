<?php

namespace App\Http\Controllers;

use App\Mail\PremiumReadyMail;
use App\Models\Purchase;
use App\Models\Test;
use App\Services\AsaasService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function __construct(private AsaasService $asaas)
    {
    }

    /** Página de checkout do relatório premium. */
    public function show(Test $test)
    {
        if ($test->status !== 'completed') {
            return redirect()->route('test.result', $test);
        }

        if ($test->isReportPaid()) {
            return redirect()->route('disc.resultDocumentoPremium', ['id' => $test->id]);
        }

        $price = (float) config('services.asaas.price', 49.00);
        $nome = $test->respondent_name ?: 'Respondente';

        return view('disc.checkout', compact('test', 'price', 'nome'));
    }

    /** Cria cliente + cobrança no Asaas e leva à página de obrigado/aguardando. */
    public function process(Request $request, Test $test)
    {
        if ($test->status !== 'completed') {
            return redirect()->route('test.result', $test);
        }

        if ($test->isReportPaid()) {
            return redirect()->route('disc.resultDocumentoPremium', ['id' => $test->id]);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'cpf_cnpj' => ['required', 'string', 'max:20'],
        ]);

        $doc = preg_replace('/\D/', '', $data['cpf_cnpj']);
        if (! in_array(strlen($doc), [11, 14], true)) {
            return back()->withInput()->withErrors([
                'cpf_cnpj' => 'Informe um CPF (11 dígitos) ou CNPJ (14 dígitos) válido.',
            ]);
        }

        $price = (float) config('services.asaas.price', 49.00);
        $phone = preg_replace('/\D/', '', (string) ($data['phone'] ?? '')) ?: null;

        try {
            // 1) Cliente no Asaas
            $customer = $this->asaas->createCustomer([
                'name' => $data['name'],
                'cpfCnpj' => $doc,
                'email' => $data['email'],
                'mobilePhone' => $phone,
                'externalReference' => 'test_'.$test->id,
                'notificationDisabled' => false,
            ]);

            // 2) Cobrança (UNDEFINED = o pagador escolhe PIX/boleto/cartão).
            // Sem callback: a confirmação é feita pela página de retorno (polling),
            // o que funciona tanto em localhost quanto em produção.
            $payment = $this->asaas->createPayment([
                'customer' => $customer['id'],
                'billingType' => 'UNDEFINED',
                'value' => $price,
                'dueDate' => now()->addDays(3)->toDateString(),
                'description' => 'Relatório DISC ONE Premium',
                'externalReference' => 'test_'.$test->id,
            ]);

            // 3) Compra interna (só depois que a cobrança deu certo)
            Purchase::create([
                'user_id' => $test->user_id,
                'test_id' => $test->id,
                'amount' => $price,
                'status' => 'pending',
                'asaas_customer_id' => $customer['id'] ?? null,
                'asaas_payment_id' => $payment['id'] ?? null,
                'invoice_url' => $payment['invoiceUrl'] ?? null,
                'payment_method' => $payment['billingType'] ?? null,
            ]);

            return redirect()->route('disc.checkout.retorno', ['test' => $test->id]);
        } catch (RequestException $e) {
            $errors = $e->response?->json('errors') ?? [];
            $msg = $errors[0]['description'] ?? 'O Asaas recusou a cobrança. Confira os dados e tente novamente.';
            Log::error('Asaas erro na cobrança', ['test' => $test->id, 'body' => $e->response?->body()]);

            return back()->withInput()->withErrors(['cpf_cnpj' => $msg]);
        } catch (\Throwable $e) {
            Log::error('Checkout falhou', ['test' => $test->id, 'erro' => $e->getMessage()]);

            return back()->withInput()->withErrors([
                'cpf_cnpj' => 'Não consegui gerar a cobrança agora. Tente novamente em instantes.',
            ]);
        }
    }

    /** Página de obrigado / aguardando pagamento. */
    public function retorno(Request $request, Test $test)
    {
        $paid = $this->verify($test);

        $purchase = $test->purchases()->latest()->first();
        $invoiceUrl = $purchase->invoice_url ?? null;
        $nome = $test->respondent_name ?: 'Respondente';

        return view('disc.checkout-retorno', compact('test', 'paid', 'nome', 'invoiceUrl'));
    }

    /** Status do pagamento em JSON (consumido pelo polling da página de retorno). */
    public function status(Test $test)
    {
        return response()->json(['paid' => $this->verify($test)]);
    }

    /** Webhook do Asaas (produção): marca pago e dispara o e-mail. */
    public function webhook(Request $request)
    {
        $event = $request->input('event');
        $payment = $request->input('payment', []);
        $paymentId = $payment['id'] ?? null;

        if ($paymentId && in_array($event, ['PAYMENT_RECEIVED', 'PAYMENT_CONFIRMED'], true)) {
            $purchase = Purchase::where('asaas_payment_id', $paymentId)->first();

            if ($purchase) {
                $this->markPaid($purchase, $payment['billingType'] ?? null);
            }
        }

        return response()->json(['ok' => true]);
    }

    /** Confirma o pagamento pela API do Asaas (libera se pago). Idempotente. */
    private function verify(Test $test): bool
    {
        $purchase = $test->purchases()->latest()->first();

        if ($purchase && $purchase->asaas_payment_id && $purchase->status !== 'paid') {
            try {
                $payment = $this->asaas->getPayment($purchase->asaas_payment_id);
                $status = $payment['status'] ?? null;

                if (in_array($status, ['RECEIVED', 'CONFIRMED', 'RECEIVED_IN_CASH'], true)) {
                    $this->markPaid($purchase, $payment['billingType'] ?? null);
                }
            } catch (\Throwable $e) {
                Log::warning('Asaas: verificação falhou', ['erro' => $e->getMessage()]);
            }
        }

        return $test->fresh()->isReportPaid();
    }

    /** Marca a compra como paga (idempotente) e envia o e-mail premium uma única vez. */
    private function markPaid(Purchase $purchase, ?string $billingType): void
    {
        if ($purchase->status === 'paid') {
            return; // já processado
        }

        $purchase->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_method' => $billingType ?: $purchase->payment_method,
        ]);

        $test = $purchase->test;

        if ($test && $test->respondent_email) {
            try {
                Mail::to($test->respondent_email)->send(new PremiumReadyMail(
                    $test->respondent_name ?: 'Respondente',
                    route('disc.resultDocumentoPremium', ['id' => $test->id]),
                ));
            } catch (\Throwable $e) {
                Log::error('Falha ao enviar e-mail premium', ['erro' => $e->getMessage()]);
            }
        }
    }
}
