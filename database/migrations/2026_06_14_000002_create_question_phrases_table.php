<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * As 4 frases de cada pergunta (24 x 4 = 96 registros).
     * O vínculo frase -> dimensão (D/I/S/C) fica SÓ aqui no backend.
     * Na tela, as frases aparecem embaralhadas; o usuário nunca vê a dimensão.
     */
    public function up(): void
    {
        Schema::create('question_phrases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->enum('dimension', ['D', 'I', 'S', 'C']); // Dominância, Influência, Estabilidade, Conformidade
            $table->text('phrase');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_phrases');
    }
};
