<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'arabic_name',
        'email',
        'password',
        'avatar',
        'phone',
        'bio',
        'gender',
        'date_of_birth',
        'locale',
        'is_active',
        // K-12 DepEd fields
        'lrn',
        'grade_level',
        'section',
        'strand',
        'school_year',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'date_of_birth'     => 'date',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    /* ---------- Relationships ---------- */

    public function coursesTaught()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'enrollments')
                    ->withPivot('progress', 'status', 'enrolled_at', 'completed_at')
                    ->withTimestamps();
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    /* ---------- Helpers ---------- */

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : asset('images/default-avatar.png');
    }

    public function getDisplayNameAttribute(): string
    {
        return app()->getLocale() === 'ar' && $this->arabic_name
            ? $this->arabic_name
            : $this->name;
    }
}
