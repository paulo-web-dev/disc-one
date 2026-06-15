<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionPhrase;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiscQuestionsSeeder extends Seeder
{
    /**
     * Popula as 24 perguntas e as 96 frases (4 por pergunta), com o
     * mapeamento frase -> dimensão (D/I/S/C). Fonte: "onedisc perguntas 2.xlsx".
     * Ordem das colunas na planilha: Dominância, Influência, Estabilidade, Conformidade.
     */
    public function run(): void
    {
        // numero => ['D' => ..., 'I' => ..., 'S' => ..., 'C' => ...]
        $questions = [
             1 => ['D' => 'Sou flexível, desapegado (a)', 'I' => 'Sou despreocupado (a), positivo (a)', 'S' => 'Sou estável, tranquilo (a)', 'C' => 'Sou questionador (a), argumentarivo (a)'],
             2 => ['D' => 'Sou produtivo', 'I' => 'Tenho facilidade de convencer as pessoas', 'S' => 'Sou pontual com as minhas obrigações', 'C' => 'Sou tradicional'],
             3 => ['D' => 'Sou prestativo', 'I' => 'Sou amável com as pessoas', 'S' => 'Sou prestativo', 'C' => 'Gosto de ter o controle da situação'],
             4 => ['D' => 'Sou preciso no que eu faço', 'I' => 'faço amigos com facilidade', 'S' => 'Sou uma pessoa verdadeira', 'C' => 'Sou inseguro nas minhas decisões'],
             5 => ['D' => 'Sou impaciente quando necessário', 'I' => 'Consigo persuadir as pessoas', 'S' => 'Gosto de trabalhar com a coletividade', 'C' => 'Sou criterioso'],
             6 => ['D' => 'Demosntro iniciativa, quero mudanças', 'I' => 'Otimista, atitude positiva perante a vida', 'S' => 'Me ajusto, concordo facilmente com os outros', 'C' => 'Respeitoso, obediente as regras'],
             7 => ['D' => 'Sou impulsivo (a)', 'I' => 'Me considero uma pessoa criativa', 'S' => 'Sou um bom companheiro (a)', 'C' => 'Tenho uma atitude reserevada'],
             8 => ['D' => 'Sou apressado (a) com as minhas coisas', 'I' => 'Me considero uma pessoa cortês', 'S' => 'Sou participativo', 'C' => 'Me considero uma pessoa pacífica'],
             9 => ['D' => 'Sou dominador (a), gosto de ter o poder', 'I' => 'Adapto-me bem as novas situações', 'S' => 'Acolho e ajudo as pessoas', 'C' => 'Gosto de ler e estudar'],
            10 => ['D' => 'Tenho uma atitude energica', 'I' => 'Sou uma pessoa animada', 'S' => 'Me considero aradável', 'C' => 'Sou contido (a)'],
            11 => ['D' => 'Estou sempre inovando', 'I' => 'Consigo infuenciar as pessoas', 'S' => 'Gosto de rotinas', 'C' => 'Procuro ser detalhista'],
            12 => ['D' => 'Gosto de ser independente para tomar as minhas decisões', 'I' => 'Me considero uma pessoas feliz', 'S' => 'Sigo ordens estabelecidas', 'C' => 'Tenho muito respeito com as pessoas'],
            13 => ['D' => 'Sou competitivo', 'I' => 'Gosto de estimular as pessoas', 'S' => 'Sou leal as minha amizades', 'C' => 'Tenho uma postura moderada'],
            14 => ['D' => 'Confio no meu potêncial', 'I' => 'Sou bem-humorado', 'S' => 'Dou assitência a quem precisa', 'C' => 'Sou cauteloso ao tomar uma decisão'],
            15 => ['D' => 'Tomo iniciativa primeiro', 'I' => 'Gosto de liberdade', 'S' => 'Sou empático', 'C' => 'Gosto de planejar'],
            16 => ['D' => 'Tomo decisões e as mantenho', 'I' => 'Gosto de expresar minhas idéias', 'S' => 'Mantenho uma atitude reservada', 'C' => 'Sigo os padrões quando possível'],
            17 => ['D' => 'Me considero uma pessoa ousada', 'I' => 'Ispiro as pessoas ao meu redor', 'S' => 'Sou obediente', 'C' => 'Gosto que tudo esteja perfeito'],
            18 => ['D' => 'Sou arrojado (a)', 'I' => 'Me considero uma pessoa divertida', 'S' => 'Gosto de cooperar com as pessoas', 'C' => 'Sou estratégista'],
            19 => ['D' => 'Gosto de fazer acontecer', 'I' => 'Gosto de estar com pessoas', 'S' => 'Gosto de fazer o que foi programado', 'C' => 'Gosto de fazer o que está na regra'],
            20 => ['D' => 'Sou audacioso (a), vou atrás dos meus objetivos', 'I' => 'Tenho uma postura entusiasta', 'S' => 'Sou uma pessoa organizada', 'C' => 'Sou justo e honesto'],
            21 => ['D' => 'Procuro alcançar grandes objetivos', 'I' => 'Tenho uma postura descontraida', 'S' => 'Sou consistente, termino o que comecei', 'C' => 'Sou disciplinado'],
            22 => ['D' => 'Gosto de ser objetivo (a)', 'I' => 'Tenho facilidade em atrair a atenção da pessoas', 'S' => 'Ajudo o próximo', 'C' => 'Sigo as regras da empresa'],
            23 => ['D' => 'Gosto de liderar', 'I' => 'Gosto de sociabilizar', 'S' => 'Sou compreensivo (a) com as falhas das pessoas', 'C' => 'Sou uma pessoa perfeccionista'],
            24 => ['D' => 'Sou ágil nas minhas decisões', 'I' => 'Sou uma pessoa espontânia', 'S' => 'Sou conciliador', 'C' => 'Procuro ser discreto, diplomático'],
        ];

        DB::transaction(function () use ($questions) {
            // Limpa antes de inserir (o cascade remove as frases junto).
            // Seguro enquanto nenhum teste respondido referenciar as frases.
            Question::query()->delete();

            foreach ($questions as $number => $phrases) {
                $question = Question::create([
                    'number' => $number,
                    'active' => true,
                ]);

                foreach (['D', 'I', 'S', 'C'] as $dimension) {
                    QuestionPhrase::create([
                        'question_id' => $question->id,
                        'dimension'   => $dimension,
                        'phrase'      => $phrases[$dimension],
                    ]);
                }
            }
        });

        $this->command->info('DISC: 24 perguntas e 96 frases inseridas com sucesso.');
    }
}
