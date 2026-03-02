<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    /**
     * Show all enrollments, with optional filters.
     */
    public function index(Request $request)
    {
        $query = Enrollment::with(['user', 'course', 'enrolledBy']);

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%"));
        }

        $enrollments = $query->latest()->paginate(20)->withQueryString();
        $courses     = Course::where('status', 'published')->orderBy('title')->get();
        $students    = User::role('student')->orderBy('name')->get();

        return view('admin.enrollments.index', compact('enrollments', 'courses', 'students'));
    }

    /**
     * Show the enrollment form.
     * Pre-populates course if ?course_id= is provided.
     */
    public function create(Request $request)
    {
        $courses  = Course::where('status', 'published')->withCount('lessons')->with('lessons', 'category')->orderBy('title')->get();
        $students = User::role('student')->orderBy('name')->get();
        $selectedCourse = $request->filled('course_id')
            ? Course::with('lessons')->find($request->course_id)
            : null;

        return view('admin.enrollments.create', compact('courses', 'students', 'selectedCourse'));
    }

    /**
     * Store a new enrollment (or batch enroll multiple students).
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id'  => 'required|exists:courses,id',
            'user_ids'   => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ]);

        $course     = Course::findOrFail($request->course_id);
        $enrolled   = 0;
        $skipped    = 0;

        foreach ($request->user_ids as $userId) {
            $exists = Enrollment::where('user_id', $userId)
                ->where('course_id', $course->id)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            Enrollment::create([
                'user_id'     => $userId,
                'course_id'   => $course->id,
                'status'      => 'active',
                'enrolled_at' => now(),
                'enrolled_by' => auth()->id(),
            ]);
            $enrolled++;
        }

        $msg = __('messages.enrolled_success', ['count' => $enrolled]);
        if ($skipped > 0) {
            $msg .= ' ' . __('messages.enrolled_skipped', ['count' => $skipped]);
        }

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', $msg);
    }

    /**
     * Update enrollment status (active / completed / dropped).
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'status' => 'required|in:active,completed,dropped',
        ]);

        $enrollment->update([
            'status'       => $request->status,
            'completed_at' => $request->status === 'completed' ? now() : $enrollment->completed_at,
        ]);

        return back()->with('success', __('messages.enrollment_updated'));
    }

    /**
     * Remove (unenroll) a student from a course.
     */
    public function destroy(Enrollment $enrollment)
    {
        $course = $enrollment->course;
        $enrollment->delete();

        return back()->with('success', __('messages.unenrolled_success'));
    }

    /**
     * AJAX: return lessons for a given course (used in the enroll form).
     */
    public function lessons(Course $course)
    {
        return response()->json(
            $course->lessons()->orderBy('order')->get(['id', 'title', 'order', 'is_free', 'status', 'duration_minutes'])
        );
    }

    /**
     * AJAX: return enrolled student IDs for a course (used to mark already-enrolled students).
     */
    public function enrolledStudents(Course $course)
    {
        return response()->json(
            Enrollment::where('course_id', $course->id)->pluck('user_id')
        );
    }
}
