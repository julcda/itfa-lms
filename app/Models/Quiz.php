<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id', 'lesson_id', 'title', 'title_ar', 'description',
        'duration_minutes', 'passing_score', 'max_attempts',
        'is_randomized', 'status',
    ];

    protected $casts = ['is_randomized' => 'boolean'];

    public function course() { return $this->belongsTo(Course::class); }
    public function lesson() { return $this->belongsTo(Lesson::class); }
    public function questions() { return $this->hasMany(QuizQuestion::class)->orderBy('order'); }
    public function attempts() { return $this->hasMany(QuizAttempt::class); }

    public function getTitleLocalizedAttribute(): string
    {
        return app()->getLocale() === 'ar' && $this->title_ar ? $this->title_ar : $this->title;
    }
}
