<?php

namespace App\Http\Controllers;

use App\Models\Test;

class DiscController extends Controller
{
    /**
     * Pesos por posição de ordenação (1 = MAIS me descreve … 4 = MENOS).
     * Definição usada nos relatórios (mais íngreme que o 4/3/2/1).
     */
    private const MAPA_PESOS = [
        1 => 4.0,
        2 => 2.5,
        3 => 1.5,
        4 => 0.5,
    ];

    /**
     * Relatório BÁSICO (gratuito) — página única, pronta para Ctrl+P.
     * Rota: /disc/documento/resultado/{id}
     */
    public function resultDocumento($id)
    {
        [$funcionario, $scores, $counts, $percentages, $perfilDominante, $nome] = $this->montarResultado($id);

        return view('disc.resultdoc', compact(
            'funcionario', 'scores', 'counts', 'percentages', 'perfilDominante', 'nome'
        ));
    }

    /**
     * Relatório PREMIUM (completo) — página única, pronta para Ctrl+P.
     * Rota: /disc/documento/premium/resultado/{id}
     */
    public function resultDocumentoPremium($id)
    {
        $test = Test::findOrFail($id);

        // Gate de pagamento: respondente/consultor só veem se a compra estiver paga.
        // Admin master vê sempre (para conferência).
        $isAdmin = auth()->check() && auth()->user()->isAdmin();
        if (! $isAdmin && ! $test->isReportPaid()) {
            return redirect()->route('disc.checkout', ['test' => $id]);
        }

        [$funcionario, $scores, $counts, $percentages, $perfilDominante, $nome] = $this->montarResultado($id);

        return view('disc.resultdocpremium', compact(
            'funcionario', 'scores', 'counts', 'percentages', 'perfilDominante', 'nome'
        ));
    }

    /**
     * Lê o teste, soma os pesos por dimensão e calcula o percentual de cada
     * dimensão de forma INDEPENDENTE (0–100% por letra), como no relatório do
     * sistema antigo. No DISC ONE a dimensão e a ordenação já ficam gravadas em
     * test_answers (dimension + order_position), então não precisa do join de frases.
     *
     * @return array{0:Test,1:array,2:array,3:array,4:string,5:string}
     */
    private function montarResultado($id): array
    {
        $funcionario = Test::with(['answers', 'user'])->findOrFail($id);

        $scores = ['D' => 0, 'I' => 0, 'S' => 0, 'C' => 0];
        $counts = ['D' => 0, 'I' => 0, 'S' => 0, 'C' => 0];

        foreach ($funcionario->answers as $resposta) {
            $dimensao = $resposta->dimension;          // D, I, S, C
            $rank = (int) $resposta->order_position;   // 1 a 4

            $peso = self::MAPA_PESOS[$rank] ?? 0;

            if (isset($scores[$dimensao])) {
                $scores[$dimensao] += $peso;
                $counts[$dimensao]++; // quantas frases aquela letra teve
            }
        }

        // Percentual por dimensão = pontos / (nº de frases da dimensão × 4).
        $percentages = [];
        foreach ($scores as $key => $val) {
            $maxPontos = $counts[$key] * 4; // máximo possível para aquela dimensão
            $percentages[$key] = $maxPontos > 0
                ? round(($val / $maxPontos) * 100)
                : 0;
        }

        $perfilDominante = array_key_first(
            collect($percentages)->sortDesc()->toArray()
        );

        // Nome do respondente: usa o campo do próprio teste (fluxo nome+e-mail,
        // quando existir) e cai para o usuário vinculado nos testes atuais.
        $nome = $funcionario->respondent_name
            ?? optional($funcionario->user)->name
            ?? 'Respondente';

        return [$funcionario, $scores, $counts, $percentages, $perfilDominante, $nome];
    }
}
