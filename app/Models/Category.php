<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'name_ar', 'slug', 'type', 'parent_id',
        'description', 'description_ar', 'icon', 'order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function parent() { return $this->belongsTo(Category::class, 'parent_id'); }
    public function children() { return $this->hasMany(Category::class, 'parent_id'); }
    public function courses() { return $this->hasMany(Course::class); }
    public function books() { return $this->hasMany(Book::class); }

    public function getNameLocalizedAttribute(): string
    {
        return app()->getLocale() === 'ar' && $this->name_ar ? $this->name_ar : $this->name;
    }
}
