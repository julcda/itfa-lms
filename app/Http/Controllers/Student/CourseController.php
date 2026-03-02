<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $enrollments = Enrollment::where('user_id', $user->id)
            ->with('course.category', 'course.teacher')
            ->paginate(12);
        return view('student.courses.index', compact('enrollments'));
    }

    public function show(Course $course)
    {
        $user = auth()->user();
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)->first();
        abort_unless($enrollment, 403, 'You are not enrolled in this course.');
        $course->load(['lessons' => fn($q) => $q->orderBy('order'), 'teacher', 'category']);
        $lessons = $course->lessons;
        return view('student.courses.show', compact('course', 'enrollment', 'lessons'));
    }

    public function downloadAttachment(Course $course, Lesson $lesson)
    {
        $user = auth()->user();
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)->first();
        abort_unless($enrollment, 403, 'You are not enrolled in this course.');
        abort_unless($lesson->attachment, 404, 'No attachment for this lesson.');

        $disk = Storage::disk('public');
        abort_unless($disk->exists($lesson->attachment), 404, 'Attachment file not found.');

        $filename = basename($lesson->attachment);
        // Preserve original file extension from the stored path
        return $disk->download($lesson->attachment, $filename);
    }

    public function lesson(Course $course, Lesson $lesson)
    {
        $user = auth()->user();
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)->first();
        abort_unless($enrollment, 403);
        $lesson->load('quizzes');
        return view('student.courses.lesson', compact('course', 'lesson', 'enrollment'));
    }
}
