<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionTitlesSeeder extends Seeder
{
    /**
     * Preenche o enunciado (title) de cada uma das 24 perguntas existentes,
     * casando pelo número da pergunta (1 a 24).
     * Rode DEPOIS do seeder que cria as perguntas.
     */
    public function run(): void
    {
        $titles = [
            1  => 'Quando estou diante de uma situação nova:',
            2  => 'No trabalho eu costumo:',
            3  => 'Quando preciso tomar decisões:',
            4  => 'Em um projeto de equipe:',
            5  => 'Quando surge um problema:',
            6  => 'Meu ritmo de trabalho costuma ser:',
            7  => 'Quando trabalho com outras pessoas:',
            8  => 'Em mudanças no trabalho:',
            9  => 'Quando tenho metas:',
            10 => 'As pessoas costumam dizer que eu:',
            11 => 'Quando participo de reuniões:',
            12 => 'Quando começo um projeto:',
            13 => 'Diante de desafios:',
            14 => 'No relacionamento com colegas:',
            15 => 'Quando tenho prazos:',
            16 => 'Quando algo não sai como esperado:',
            17 => 'No dia a dia profissional:',
            18 => 'Em ambientes de trabalho:',
            19 => 'Quando preciso convencer alguém:',
            20 => 'Quando trabalho sob pressão:',
            21 => 'No trabalho eu valorizo:',
            22 => 'Quando começo algo novo:',
            23 => 'No meu estilo de trabalho:',
            24 => 'Normalmente eu sou visto como alguém que:',
        ];

        foreach ($titles as $number => $title) {
            Question::where('number', $number)->update(['title' => $title]);
        }
    }
}
