<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'score_d', 'score_i', 'score_s', 'score_c',
        'percent_d', 'percent_i', 'percent_s', 'percent_c',
        'dominant_profile',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'score_d' => 'integer',
        'score_i' => 'integer',
        'score_s' => 'integer',
        'score_c' => 'integer',
        'percent_d' => 'float',
        'percent_i' => 'float',
        'percent_s' => 'float',
        'percent_c' => 'float',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * As respostas (96 quando completo) deste teste.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(TestAnswer::class);
    }

    /**
     * Compras associadas a este teste (liberação do relatório).
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Existe alguma compra paga liberando o relatório deste teste?
     */
    public function isReportPaid(): bool
    {
        return $this->purchases()->where('status', 'paid')->exists();
    }
}
