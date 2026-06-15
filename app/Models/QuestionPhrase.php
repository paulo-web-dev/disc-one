<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionPhrase extends Model
{
    protected $fillable = [
        'question_id',
        'dimension',
        'phrase',
    ];

    /**
     * A pergunta à qual esta frase pertence.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
