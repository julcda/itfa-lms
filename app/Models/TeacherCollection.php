<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeacherCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'name_ar', 'description',
        'cover_color', 'parent_id', 'created_by',
        'is_private', 'sort_order', 'icon',
    ];

    protected $casts = [
        'is_private' => 'boolean',
    ];

    // ── Relationships ─────────────────────────────────────────────

    public function parent(): BelongsTo
    {
        return $this->belongsTo(TeacherCollection::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(TeacherCollection::class, 'parent_id')->orderBy('sort_order');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(
            TeacherMaterial::class,
            'teacher_collection_material',
            'teacher_collection_id',
            'teacher_material_id'
        )->withPivot('sort_order')->orderBy('teacher_collection_material.sort_order')->withTimestamps();
    }

    // ── Accessors ─────────────────────────────────────────────────

    public function getNameLocalizedAttribute(): string
    {
        return app()->getLocale() === 'ar' && $this->name_ar ? $this->name_ar : $this->name;
    }

    /** Total material count including all sub-collections */
    public function getTotalMaterialCountAttribute(): int
    {
        $count = $this->materials()->count();
        foreach ($this->children as $child) {
            $count += $child->total_material_count;
        }
        return $count;
    }

    // ── Scopes ───────────────────────────────────────────────────

    /** Only root-level collections */
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    /** Collections visible to the given user */
    public function scopeVisibleTo($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('is_private', false)
              ->orWhere('created_by', $userId);
        });
    }

    // ── Helpers ───────────────────────────────────────────────────

    /** Palette of predefined cover colors */
    public static function colorPalette(): array
    {
        return [
            '#10b981' => 'Emerald',
            '#3b82f6' => 'Blue',
            '#8b5cf6' => 'Violet',
            '#f59e0b' => 'Amber',
            '#ef4444' => 'Red',
            '#ec4899' => 'Pink',
            '#06b6d4' => 'Cyan',
            '#f97316' => 'Orange',
            '#6366f1' => 'Indigo',
            '#14b8a6' => 'Teal',
            '#84cc16' => 'Lime',
            '#64748b' => 'Slate',
        ];
    }

    public static function iconOptions(): array
    {
        return ['📁', '📚', '📖', '📝', '🎓', '🔬', '🎨', '🎵', '🌍', '🏫', '📊', '🎯', '💡', '⭐', '🗂️', '📌'];
    }
}
