<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'number',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * As 4 frases desta pergunta (uma por dimensão D/I/S/C).
     */
    public function phrases(): HasMany
    {
        return $this->hasMany(QuestionPhrase::class);
    }
}
