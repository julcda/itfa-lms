<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'course_id', 'certificate_number',
        'issued_at', 'expiry_date', 'file_path',
    ];

    protected $casts = [
        'issued_at'   => 'datetime',
        'expiry_date' => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function course() { return $this->belongsTo(Course::class); }

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? asset('storage/'.$this->file_path) : null;
    }

    public static function generateNumber(): string
    {
        return 'ITFA-' . strtoupper(uniqid());
    }
}
