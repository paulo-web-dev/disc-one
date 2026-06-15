<?php

namespace App\Services;

use App\Models\Test;
use Illuminate\Support\Collection;
use RuntimeException;

class DiscCalculatorService
{
    /** 24 perguntas × (4 + 3 + 2 + 1) */
    public const TOTAL_POINTS = 240;

    private const DIMENSIONS = ['D', 'I', 'S', 'C'];

    /**
     * Calcula scores, percentuais e perfil dominante de um teste e grava no banco.
     */
    public function calculate(Test $test): Test
    {
        $answers = $test->answers()->get();

        $this->validate($answers);

        // Soma os pesos por dimensão.
        $scores = ['D' => 0, 'I' => 0, 'S' => 0, 'C' => 0];
        foreach ($answers as $answer) {
            $scores[$answer->dimension] += $answer->weight;
        }

        $total = array_sum($scores); // deve ser 240

        // Percentual de cada dimensão sobre o total distribuído.
        $percents = [];
        foreach ($scores as $dimension => $score) {
            $percents[$dimension] = $total > 0 ? round(($score / $total) * 100, 2) : 0.0;
        }

        // Perfil dominante = maior pontuação (desempate na ordem D, I, S, C).
        $dominant = self::DIMENSIONS[0];
        foreach (self::DIMENSIONS as $dimension) {
            if ($scores[$dimension] > $scores[$dominant]) {
                $dominant = $dimension;
            }
        }

        $test->update([
            'score_d' => $scores['D'],
            'score_i' => $scores['I'],
            'score_s' => $scores['S'],
            'score_c' => $scores['C'],
            'percent_d' => $percents['D'],
            'percent_i' => $percents['I'],
            'percent_s' => $percents['S'],
            'percent_c' => $percents['C'],
            'dominant_profile' => $dominant,
        ]);

        return $test->refresh();
    }

    /**
     * Garante que o teste está completo e consistente antes de calcular:
     * 96 respostas, 24 perguntas, cada uma com as ordens 1,2,3,4 sem repetição.
     */
    private function validate(Collection $answers): void
    {
        if ($answers->count() !== 96) {
            throw new RuntimeException(
                "Teste incompleto: esperadas 96 respostas, encontradas {$answers->count()}."
            );
        }

        $byQuestion = $answers->groupBy('question_id');

        if ($byQuestion->count() !== 24) {
            throw new RuntimeException(
                "Teste incompleto: esperadas 24 perguntas, encontradas {$byQuestion->count()}."
            );
        }

        foreach ($byQuestion as $questionId => $group) {
            $positions = $group->pluck('order_position')
                ->map(fn ($p) => (int) $p)
                ->sort()
                ->values()
                ->all();

            if ($positions !== [1, 2, 3, 4]) {
                throw new RuntimeException(
                    "Pergunta {$questionId}: ordenação inválida (precisa ser 1, 2, 3 e 4 sem repetir)."
                );
            }
        }
    }
}
