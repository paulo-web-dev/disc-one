<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Enunciado de cada pergunta (ex.: "Quando estou diante de uma situação nova:"). */
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('title')->nullable()->after('number');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }
};
