<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AsaasService
{
    private string $baseUrl;
    private string $key;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.asaas.base_url'), '/');
        $this->key = (string) config('services.asaas.key');
    }

    private function http()
    {
        return Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'access_token' => $this->key,
                'User-Agent'   => 'DiscOne/1.0',
            ])
            ->acceptJson()
            ->asJson();
    }

    /** Cria um cliente no Asaas. Retorna o JSON (com id em ['id']). */
    public function createCustomer(array $data): array
    {
        return $this->http()->post('/customers', $data)->throw()->json();
    }

    /** Cria uma cobrança. Retorna o JSON (id, invoiceUrl, status, billingType…). */
    public function createPayment(array $data): array
    {
        return $this->http()->post('/payments', $data)->throw()->json();
    }

    /** Consulta uma cobrança pelo id do Asaas. */
    public function getPayment(string $paymentId): array
    {
        return $this->http()->get('/payments/'.$paymentId)->throw()->json();
    }
}
