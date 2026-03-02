<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id', 'title', 'title_ar', 'description', 'description_ar',
        'content', 'content_ar', 'video_url', 'attachment',
        'duration_minutes', 'order', 'is_free', 'status',
    ];

    protected $casts = ['is_free' => 'boolean'];

    public function course() { return $this->belongsTo(Course::class); }
    public function quizzes() { return $this->hasMany(Quiz::class); }

    public function getTitleLocalizedAttribute(): string
    {
        return app()->getLocale() === 'ar' && $this->title_ar ? $this->title_ar : $this->title;
    }
}
