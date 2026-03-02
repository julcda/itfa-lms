<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $attendances = Attendance::with(['user', 'course', 'markedBy'])
            ->latest()->paginate(15)->withQueryString();
        $courses = Course::where('status', 'published')->get();
        return view('admin.attendances.index', compact('attendances', 'courses'));
    }

    public function byCourse(Course $course)
    {
        $course->load(['enrollments.user']);
        $students = $course->enrollments->map->user->filter();
        $attendances = Attendance::where('course_id', $course->id)
            ->get()->groupBy(fn($a) => $a->session_date->format('Y-m-d'));
        return view('admin.attendances.by-course', compact('course', 'students', 'attendances'));
    }

    public function create()
    {
        $courses = Course::where('status', 'published')->get();
        return view('admin.attendances.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id'     => 'required|exists:courses,id',
            'session_date'  => 'required|date',
            'session_title' => 'nullable|string|max:255',
            'statuses'      => 'required|array',
            'statuses.*'    => 'in:present,absent,late,excused',
        ]);

        foreach ($request->statuses as $userId => $status) {
            Attendance::updateOrCreate(
                ['user_id' => $userId, 'course_id' => $request->course_id, 'session_date' => $request->session_date],
                ['session_title' => $request->session_title, 'status' => $status, 'marked_by' => auth()->id()]
            );
        }

        return back()->with('success', 'Attendance recorded successfully.');
    }

    public function show(Attendance $attendance)
    {
        return back();
    }

    public function edit(Attendance $attendance)
    {
        return view('admin.attendances.edit', compact('attendance'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'status' => 'required|in:present,absent,late,excused',
            'notes'  => 'nullable|string',
        ]);
        $attendance->update([
            'status'    => $request->status,
            'notes'     => $request->notes,
            'marked_by' => auth()->id(),
        ]);
        return back()->with('success', 'Attendance updated.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return back()->with('success', 'Record deleted.');
    }
}
