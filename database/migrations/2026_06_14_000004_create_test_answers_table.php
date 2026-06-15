<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Resposta individual de cada frase em cada teste (96 linhas por teste completo).
     * order_position = a ordem que o usuário deu (1 = mais descreve ... 4 = menos descreve).
     * weight = peso derivado da ordem -> 1->4, 2->3, 3->2, 4->1.
     */
    public function up(): void
    {
        Schema::create('test_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained();
            // phrase_id aponta para question_phrases (precisa informar a tabela)
            $table->foreignId('phrase_id')->constrained('question_phrases');
            $table->enum('dimension', ['D', 'I', 'S', 'C']); // redundante, mas agiliza a soma por dimensão
            $table->unsignedTinyInteger('order_position');     // 1..4
            $table->unsignedTinyInteger('weight');             // 4,3,2,1
            $table->timestamps();

            // Cada frase só pode ser respondida uma vez por teste
            $table->unique(['test_id', 'phrase_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_answers');
    }
};
