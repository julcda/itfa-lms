<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id', 'question_text', 'question_text_ar', 'type',
        'options', 'correct_answer', 'explanation', 'explanation_ar',
        'points', 'order',
    ];

    protected $casts = ['options' => 'array'];

    public function quiz() { return $this->belongsTo(Quiz::class); }

    public function getQuestionLocalizedAttribute(): string
    {
        return app()->getLocale() === 'ar' && $this->question_text_ar ? $this->question_text_ar : $this->question_text;
    }
}
