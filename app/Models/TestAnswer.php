<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestAnswer extends Model
{
    protected $fillable = [
        'test_id',
        'question_id',
        'phrase_id',
        'dimension',
        'order_position',
        'weight',
    ];

    protected $casts = [
        'order_position' => 'integer',
        'weight' => 'integer',
    ];

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * A frase respondida (aponta para question_phrases).
     */
    public function phrase(): BelongsTo
    {
        return $this->belongsTo(QuestionPhrase::class, 'phrase_id');
    }
}
