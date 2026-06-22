<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Adiciona 'consultant' ao enum da coluna users.role
     * (antes só aceitava 'admin' e 'respondent').
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin', 'respondent', 'consultant') NOT NULL DEFAULT 'respondent'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin', 'respondent') NOT NULL DEFAULT 'respondent'");
    }
};
