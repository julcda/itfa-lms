<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Book;
use App\Models\Enrollment;
use App\Models\Attendance;
use App\Models\Certificate;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'students'     => User::role('student')->count(),
            'teachers'     => User::role('teacher')->count(),
            'courses'      => Course::count(),
            'books'        => Book::count(),
            'enrollments'  => Enrollment::count(),
            'certificates' => Certificate::count(),
        ];

        $recentEnrollments = Enrollment::with('user', 'course')
            ->latest()
            ->take(10)
            ->get();

        $recentUsers = User::latest()->take(8)->get();

        return view('admin.dashboard', compact('stats', 'recentEnrollments', 'recentUsers'));
    }
}
