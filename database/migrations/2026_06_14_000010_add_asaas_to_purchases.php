<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pagamento via Asaas:
     * - user_id passa a ser nullable (respondentes sem conta também compram).
     * - guarda os identificadores do Asaas e a URL da fatura.
     */
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        DB::statement('ALTER TABLE `purchases` MODIFY `user_id` BIGINT UNSIGNED NULL');

        Schema::table('purchases', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();

            $table->string('asaas_customer_id')->nullable()->after('status');
            $table->string('asaas_payment_id')->nullable()->after('asaas_customer_id');
            $table->string('invoice_url')->nullable()->after('asaas_payment_id');
        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['asaas_customer_id', 'asaas_payment_id', 'invoice_url']);
        });

        DB::statement('ALTER TABLE `purchases` MODIFY `user_id` BIGINT UNSIGNED NOT NULL');

        Schema::table('purchases', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
