<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    protected $fillable = [
        'user_id',
        'test_id',
        'amount',
        'status',
        'asaas_customer_id',
        'asaas_payment_id',
        'invoice_url',
        'payment_method',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'float',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * O teste (relatório) que esta compra libera.
     */
    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    /**
     * Escopo: apenas compras pagas. Uso: Purchase::paid()->...
     */
    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', 'paid');
    }
}
