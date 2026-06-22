<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Celular/WhatsApp do respondente (obrigatório no início do teste). */
    public function up(): void
    {
        Schema::table('tests', function (Blueprint $table) {
            $table->string('respondent_phone')->nullable()->after('respondent_email');
        });
    }

    public function down(): void
    {
        Schema::table('tests', function (Blueprint $table) {
            $table->dropColumn('respondent_phone');
        });
    }
};
