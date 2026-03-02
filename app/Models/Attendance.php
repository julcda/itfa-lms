<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'course_id', 'session_date', 'session_title',
        'session_title_ar', 'status', 'notes', 'marked_by',
    ];

    protected $casts = ['session_date' => 'date'];

    public function user() { return $this->belongsTo(User::class); }
    public function course() { return $this->belongsTo(Course::class); }
    public function markedBy() { return $this->belongsTo(User::class, 'marked_by'); }
}
