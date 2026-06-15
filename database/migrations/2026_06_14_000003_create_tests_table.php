<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cada teste respondido (uma "tentativa").
     * Pontuação bruta por dimensão: 0 a 96 (24 perguntas, peso máximo 4 por frase).
     * Total dos 4 scores = 240 por teste completo. Percentuais somam ~100.
     */
    public function up(): void
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();

            // Nullable: o teste pode ser criado ao iniciar (sem usuário ainda) e
            // ser vinculado quando o respondente se identifica (o layout captura
            // nome/e-mail ao final). Fechamos o fluxo exato nas Etapas 6/7.
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->enum('status', ['pending', 'completed'])->default('pending');

            // Pontuação bruta por dimensão (0–96 cada)
            $table->unsignedSmallInteger('score_d')->default(0);
            $table->unsignedSmallInteger('score_i')->default(0);
            $table->unsignedSmallInteger('score_s')->default(0);
            $table->unsignedSmallInteger('score_c')->default(0);

            // Percentual por dimensão (somam ~100)
            $table->decimal('percent_d', 5, 2)->default(0);
            $table->decimal('percent_i', 5, 2)->default(0);
            $table->decimal('percent_s', 5, 2)->default(0);
            $table->decimal('percent_c', 5, 2)->default(0);

            $table->enum('dominant_profile', ['D', 'I', 'S', 'C'])->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
