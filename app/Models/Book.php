<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'title_ar', 'author', 'author_ar', 'description', 'description_ar',
        'category_id', 'cover_image', 'file_path', 'file_type', 'external_url',
        'isbn', 'published_year', 'language', 'tags', 'status',
        'uploaded_by', 'download_count', 'view_count',
        // K-12 DepEd fields
        'grade_level', 'learning_area', 'deped_code', 'edition',
    ];

    protected $casts = ['tags' => 'array'];

    public function category() { return $this->belongsTo(Category::class); }
    public function uploader() { return $this->belongsTo(User::class, 'uploaded_by'); }

    public function getTitleLocalizedAttribute(): string
    {
        return app()->getLocale() === 'ar' && $this->title_ar ? $this->title_ar : $this->title;
    }

    public function getCoverUrlAttribute(): string
    {
        return $this->cover_image ? asset('storage/'.$this->cover_image) : asset('images/default-book.png');
    }
}
