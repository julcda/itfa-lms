<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Certificate;
use App\Models\Enrollment;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $enrollments = Enrollment::where('user_id', $user->id)
            ->with('course.teacher')
            ->latest()
            ->take(6)
            ->get();
        $certificates = Certificate::where('user_id', $user->id)
            ->with('course')
            ->latest()
            ->take(4)
            ->get();
        $attendanceStats = Attendance::where('user_id', $user->id)
            ->get()
            ->groupBy('status')
            ->map->count();

        return view('student.dashboard', compact(
            'enrollments', 'certificates', 'attendanceStats'
        ));
    }
}
