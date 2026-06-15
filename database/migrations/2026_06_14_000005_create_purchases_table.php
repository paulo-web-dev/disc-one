<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Controle de pagamento. No nosso modelo, o teste e o resultado na tela são
     * gratuitos; a compra libera o DOWNLOAD do relatório completo (PDF) de um teste.
     */
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // O teste (relatório) que esta compra libera.
            $table->foreignId('test_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 8, 2);
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->string('payment_method')->nullable(); // pix, cartão, etc.
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
