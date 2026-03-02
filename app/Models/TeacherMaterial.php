<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'title_ar',
        'description', 'description_ar',
        'subject', 'grade_level', 'category_id',
        'material_type', 'cover_image', 'file_path',
        'external_url', 'language', 'tags',
        'source', 'published_year', 'status',
        'view_count', 'download_count', 'uploaded_by',
    ];

    protected $casts = ['tags' => 'array'];

    // ── Relationships ────────────────────────────────────────────
    public function category()  { return $this->belongsTo(Category::class); }
    public function uploader()  { return $this->belongsTo(User::class, 'uploaded_by'); }

    // ── Accessors ─────────────────────────────────────────────────
    public function getTitleLocalizedAttribute(): string
    {
        return app()->getLocale() === 'ar' && $this->title_ar ? $this->title_ar : $this->title;
    }

    public function getCoverUrlAttribute(): string
    {
        return $this->cover_image
            ? asset('storage/' . $this->cover_image)
            : asset('images/default-material.png');
    }

    // ── Helpers ───────────────────────────────────────────────────
    /** Human-readable label for the material type */
    public function getTypeIconAttribute(): string
    {
        return match ($this->material_type) {
            'pdf'         => '📄',
            'ppt'         => '📊',
            'video'       => '🎬',
            'audio'       => '🎵',
            'doc'         => '📝',
            'spreadsheet' => '📉',
            'image'       => '🖼️',
            'link'        => '🔗',
            default       => '📁',
        };
    }

    /** Tailwind color classes per material type */
    public static function typeColor(string $type): string
    {
        return match ($type) {
            'pdf'         => 'bg-red-100 text-red-700',
            'ppt'         => 'bg-orange-100 text-orange-700',
            'video'       => 'bg-blue-100 text-blue-700',
            'audio'       => 'bg-purple-100 text-purple-700',
            'doc'         => 'bg-emerald-100 text-emerald-700',
            'spreadsheet' => 'bg-teal-100 text-teal-700',
            'image'       => 'bg-pink-100 text-pink-700',
            'link'        => 'bg-sky-100 text-sky-700',
            default       => 'bg-gray-100 text-gray-600',
        };
    }

    public static function allTypes(): array
    {
        return ['pdf', 'ppt', 'video', 'audio', 'doc', 'spreadsheet', 'image', 'link', 'other'];
    }
}
