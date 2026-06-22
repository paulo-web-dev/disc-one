<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Permite responder o teste SEM conta: o próprio teste guarda o
     * nome e o e-mail do respondente. (user_id já é nullable desde a
     * criação da tabela, então testes anônimos têm user_id = null.)
     */
    public function up(): void
    {
        Schema::table('tests', function (Blueprint $table) {
            $table->string('respondent_name')->nullable()->after('user_id');
            $table->string('respondent_email')->nullable()->after('respondent_name');
        });
    }

    public function down(): void
    {
        Schema::table('tests', function (Blueprint $table) {
            $table->dropColumn(['respondent_name', 'respondent_email']);
        });
    }
};
