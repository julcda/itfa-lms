<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    // DepEd K-12 grade level constants
    public const GRADE_LEVELS = [
        'kindergarten' => 'Kindergarten',
        'grade_1'  => 'Grade 1',  'grade_2'  => 'Grade 2',  'grade_3'  => 'Grade 3',
        'grade_4'  => 'Grade 4',  'grade_5'  => 'Grade 5',  'grade_6'  => 'Grade 6',
        'grade_7'  => 'Grade 7',  'grade_8'  => 'Grade 8',  'grade_9'  => 'Grade 9',  'grade_10' => 'Grade 10',
        'grade_11' => 'Grade 11', 'grade_12' => 'Grade 12',
    ];

    public const LEARNING_AREAS = [
        'mother_tongue'       => 'Mother Tongue',
        'filipino'            => 'Filipino',
        'english'             => 'English',
        'mathematics'         => 'Mathematics',
        'science'             => 'Science',
        'araling_panlipunan'  => 'Araling Panlipunan (AP)',
        'esp'                 => 'Edukasyon sa Pagpapahalaga (EsP)',
        'mapeh'               => 'MAPEH',
        'tle_epp'             => 'TLE / EPP',
        'shs_core'            => 'SHS Core Subject',
        'applied'             => 'Applied Subject',
        'specialized'         => 'Specialized Subject',
        'other'               => 'Other',
    ];

    public const STRANDS = [
        'stem'      => 'STEM',
        'abm'       => 'ABM',
        'humss'     => 'HUMSS',
        'gas'       => 'GAS',
        'tvl_he'    => 'TVL – Home Economics',
        'tvl_ict'   => 'TVL – ICT',
        'tvl_ia'    => 'TVL – Industrial Arts',
        'tvl_af'    => 'TVL – Agri-Fishery Arts',
        'sports'    => 'Sports Track',
        'arts_design' => 'Arts & Design Track',
    ];

    public const QUARTERS = ['Q1' => 'Quarter 1', 'Q2' => 'Quarter 2', 'Q3' => 'Quarter 3', 'Q4' => 'Quarter 4'];

    protected $fillable = [
        'title', 'title_ar', 'slug', 'description', 'description_ar',
        'category_id', 'teacher_id', 'thumbnail', 'status', 'level',
        'duration_hours', 'is_featured', 'order', 'language',
        // K-12 DepEd fields
        'grade_level', 'learning_area', 'quarter', 'school_year', 'strand', 'subject_code',
    ];

    protected $casts = ['is_featured' => 'boolean'];

    public function category() { return $this->belongsTo(Category::class); }
    public function teacher() { return $this->belongsTo(User::class, 'teacher_id'); }
    public function lessons() { return $this->hasMany(Lesson::class)->orderBy('order'); }
    public function enrollments() { return $this->hasMany(Enrollment::class); }
    public function students() { return $this->belongsToMany(User::class, 'enrollments')->withPivot('progress', 'status')->withTimestamps(); }
    public function quizzes() { return $this->hasMany(Quiz::class); }
    public function attendances() { return $this->hasMany(Attendance::class); }
    public function certificates() { return $this->hasMany(Certificate::class); }

    public function getTitleLocalizedAttribute(): string
    {
        return app()->getLocale() === 'ar' && $this->title_ar ? $this->title_ar : $this->title;
    }

    public function getThumbnailUrlAttribute(): string
    {
        return $this->thumbnail ? asset('storage/'.$this->thumbnail) : asset('images/default-course.png');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($c) => $c->slug = $c->slug ?: Str::slug($c->title));
    }
}
