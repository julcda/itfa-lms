<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('teacher', 'category')
            ->when(request('search'), fn($q, $s) => $q->where('title', 'like', "%$s%")->orWhere('title_ar', 'like', "%$s%"))
            ->when(request('status'), fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(15);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $teachers = User::role('teacher')->get();
        return view('admin.courses.create', compact('categories', 'teachers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'title_ar'       => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'description_ar' => 'nullable|string',
            'category_id'    => 'nullable|exists:categories,id',
            'teacher_id'     => 'required|exists:users,id',
            'status'         => 'in:draft,published,archived',
            'level'          => 'in:beginner,intermediate,advanced',
            'duration_hours' => 'integer|min:0',
            'is_featured'    => 'boolean',
            'language'       => 'string|max:5',
            'thumbnail'      => 'nullable|image|max:2048',
            // K-12 DepEd fields
            'grade_level'    => 'nullable|string|max:20',
            'learning_area'  => 'nullable|string|max:50',
            'quarter'        => 'nullable|in:Q1,Q2,Q3,Q4',
            'school_year'    => 'nullable|string|max:20',
            'strand'         => 'nullable|string|max:30',
            'subject_code'   => 'nullable|string|max:60',
        ]);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }
        $data['is_featured'] = $request->boolean('is_featured');
        $data['slug'] = Str::slug($data['title']);
        Course::create($data);

        return redirect()->route('admin.courses.index')->with('success', __('messages.course_created'));
    }

    public function show(Course $course)
    {
        $course->load('teacher', 'category', 'lessons', 'enrollments.user');
        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $categories = Category::where('is_active', true)->get();
        $teachers = User::role('teacher')->get();
        return view('admin.courses.edit', compact('course', 'categories', 'teachers'));
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'title_ar'       => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'description_ar' => 'nullable|string',
            'category_id'    => 'nullable|exists:categories,id',
            'teacher_id'     => 'required|exists:users,id',
            'status'         => 'in:draft,published,archived',
            'level'          => 'in:beginner,intermediate,advanced',
            'duration_hours' => 'integer|min:0',
            'is_featured'    => 'boolean',
            'language'       => 'string|max:5',
            'thumbnail'      => 'nullable|image|max:2048',
            // K-12 DepEd fields
            'grade_level'    => 'nullable|string|max:20',
            'learning_area'  => 'nullable|string|max:50',
            'quarter'        => 'nullable|in:Q1,Q2,Q3,Q4',
            'school_year'    => 'nullable|string|max:20',
            'strand'         => 'nullable|string|max:30',
            'subject_code'   => 'nullable|string|max:60',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) Storage::disk('public')->delete($course->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }
        $data['is_featured'] = $request->boolean('is_featured');

        $course->update($data);
        return redirect()->route('admin.courses.index')->with('success', __('messages.course_updated'));
    }

    public function destroy(Course $course)
    {
        if ($course->thumbnail) Storage::disk('public')->delete($course->thumbnail);
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', __('messages.course_deleted'));
    }
}
