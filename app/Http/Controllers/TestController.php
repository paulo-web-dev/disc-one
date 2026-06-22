<?php

namespace App\Http\Controllers;

use App\Mail\TestCompletedMail;
use App\Models\Question;
use App\Models\Test;
use App\Models\TestAnswer;
use App\Models\User;
use App\Services\DiscCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    private const TOTAL_QUESTIONS = 24;

    /** Tela de início: instruções + identificação (nome + e-mail), sem login. */
    public function intro()
    {
        return view('test.intro');
    }

    /** Cria um teste (sem conta) com nome + e-mail e leva à primeira pergunta. */
    public function start(Request $request)
    {
        $data = $request->validate([
            'respondent_name'  => ['required', 'string', 'max:255'],
            'respondent_email' => ['required', 'email', 'max:255'],
            'respondent_phone' => ['required', 'string', 'max:20'],
        ], [], [
            'respondent_name'  => 'nome',
            'respondent_email' => 'e-mail',
            'respondent_phone' => 'celular',
        ]);

        $test = Test::create([
            'respondent_name'  => $data['respondent_name'],
            'respondent_email' => $data['respondent_email'],
            'respondent_phone' => $data['respondent_phone'],
            'consultant_id'    => $this->resolveConsultantId($request),
            'status'           => 'pending',
            'started_at'       => now(),
        ]);

        return redirect()->route('test.question', [$test, 1]);
    }

    /** Exibe uma pergunta: 4 frases embaralhadas + ordenação 1-4. */
    public function question(Test $test, $n)
    {
        if ($test->status === 'completed') {
            return redirect()->route('test.result', $test);
        }

        $n = $this->clampQuestion((int) $n);
        $question = Question::where('number', $n)->with('phrases')->firstOrFail();

        // Embaralhamento ESTÁVEL por teste: a ordem não muda ao voltar/avançar,
        // mas varia de teste pra teste e não segue D-I-S-C.
        $phrases = $question->phrases
            ->sortBy(fn ($p) => md5($test->id.'-'.$p->id))
            ->values();

        // Ordenação já escolhida nesta pergunta (para pré-marcar os botões).
        $existing = $test->answers()
            ->where('question_id', $question->id)
            ->pluck('order_position', 'phrase_id');

        return view('test.question', [
            'test' => $test,
            'question' => $question,
            'phrases' => $phrases,
            'n' => $n,
            'total' => self::TOTAL_QUESTIONS,
            'answeredCount' => $this->answeredCount($test),
            'existing' => $existing,
        ]);
    }

    /** Salva a ordenação de uma pergunta e avança (ou finaliza na última). */
    public function saveAnswer(Request $request, Test $test, $n)
    {
        if ($test->status === 'completed') {
            return redirect()->route('test.result', $test);
        }

        $n = $this->clampQuestion((int) $n);
        $question = Question::where('number', $n)->with('phrases')->firstOrFail();

        $data = $request->validate([
            'order' => ['required', 'array', 'size:4'],
            'order.*' => ['required', 'integer', 'between:1,4'],
        ]);
        $order = $data['order'];

        // As chaves precisam ser exatamente as 4 frases desta pergunta...
        $keys = array_map('intval', array_keys($order));
        sort($keys);
        $expected = $question->phrases->pluck('id')->map('intval')->sort()->values()->all();

        // ...e as posições precisam ser uma permutação de 1,2,3,4 (sem repetir).
        $positions = array_map('intval', array_values($order));
        sort($positions);

        if ($keys !== $expected || $positions !== [1, 2, 3, 4]) {
            return back()->withErrors([
                'order' => 'Ordene as 4 frases de 1 a 4, sem repetir números.',
            ]);
        }

        $phrasesById = $question->phrases->keyBy('id');

        DB::transaction(function () use ($order, $test, $question, $phrasesById) {
            foreach ($order as $phraseId => $position) {
                $phraseId = (int) $phraseId;
                $position = (int) $position;

                TestAnswer::updateOrCreate(
                    ['test_id' => $test->id, 'phrase_id' => $phraseId],
                    [
                        'question_id' => $question->id,
                        'dimension' => $phrasesById[$phraseId]->dimension,
                        'order_position' => $position,
                        'weight' => 5 - $position, // 1->4, 2->3, 3->2, 4->1
                    ]
                );
            }
        });

        if ($n < self::TOTAL_QUESTIONS) {
            return redirect()->route('test.question', [$test, $n + 1]);
        }

        return $this->finalize($test);
    }

    /** Tela de resultado: gráfico + perfil dominante. */
    public function result(Test $test)
    {

        // Se ainda não foi concluído, volta pro fluxo.
        if ($test->status !== 'completed') {
            return redirect()->route('test.question', [$test, $this->firstUnansweredNumber($test)]);
        }

        // Recalcula testes concluídos antes da Etapa 7B (que ainda não tinham score).
        if (is_null($test->dominant_profile)) {
            app(DiscCalculatorService::class)->calculate($test);
            $test->refresh();
        }

        $profile = config('disc_profiles.'.$test->dominant_profile);

        return view('test.result', compact('test', 'profile'));
    }

    // ---------------------------------------------------------------- helpers

    /** Conclui o teste, valida a completude e dispara o cálculo. */
    private function finalize(Test $test)
    {
        $missing = $this->firstUnansweredNumber($test, onlyIfIncomplete: true);

        if ($missing !== null) {
            return redirect()
                ->route('test.question', [$test, $missing])
                ->withErrors(['order' => "Você ainda não respondeu a pergunta {$missing}."]);
        }

        $test->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        app(DiscCalculatorService::class)->calculate($test);

        $this->sendCompletionEmail($test);

        return redirect()->route('test.result', $test);
    }

    /** Lê o cookie de referral e devolve o id do consultor, se válido. */
    private function resolveConsultantId(Request $request): ?int
    {
        $ref = $request->cookie('ref');

        if (! $ref) {
            return null;
        }

        $isConsultant = User::where('id', $ref)->where('role', 'consultant')->exists();

        return $isConsultant ? (int) $ref : null;
    }

    /** Envia o e-mail de agradecimento com o link do relatório básico. */
    private function sendCompletionEmail(Test $test): void
    {
        $email = $test->respondent_email ?? optional($test->user)->email;

        if (! $email) {
            return;
        }

        try {
            Mail::to($email)->send(new TestCompletedMail($test));
        } catch (\Throwable $e) {
            // Um erro de SMTP não pode quebrar o fluxo do respondente.
            Log::error('Falha ao enviar e-mail de conclusão do teste', [
                'test_id' => $test->id,
                'erro' => $e->getMessage(),
            ]);
        }
    }

    private function clampQuestion(int $n): int
    {
        return max(1, min(self::TOTAL_QUESTIONS, $n));
    }

    /** Quantas perguntas já têm as 4 frases respondidas. */
    private function answeredCount(Test $test): int
    {
        return $test->answers()
            ->select('question_id')
            ->groupBy('question_id')
            ->havingRaw('COUNT(*) = 4')
            ->get()
            ->count();
    }

    /** Número da primeira pergunta ainda incompleta (1-24). */
    private function firstUnansweredNumber(Test $test, bool $onlyIfIncomplete = false): ?int
    {
        $completeQuestionIds = $test->answers()
            ->select('question_id')
            ->groupBy('question_id')
            ->havingRaw('COUNT(*) = 4')
            ->pluck('question_id');

        $completeNumbers = Question::whereIn('id', $completeQuestionIds)->pluck('number')->all();

        for ($i = 1; $i <= self::TOTAL_QUESTIONS; $i++) {
            if (! in_array($i, $completeNumbers, true)) {
                return $i;
            }
        }

        return $onlyIfIncomplete ? null : self::TOTAL_QUESTIONS;
    }
}
