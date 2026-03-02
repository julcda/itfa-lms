<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    public function index(Course $course)
    {
        return redirect()->route('admin.courses.show', $course);
    }

    public function create(Course $course)
    {
        return view('admin.lessons.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'title_ar'         => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            'description_ar'   => 'nullable|string',
            'content'          => 'nullable|string',
            'content_ar'       => 'nullable|string',
            'video_url'        => 'nullable|url',
            'attachment'       => 'nullable|file|max:20480',
            'duration_minutes' => 'integer|min:0',
            'order'            => 'integer|min:0',
            'is_free'          => 'boolean',
            'status'           => 'in:draft,published',
        ]);
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('lessons/attachments', 'public');
        }
        $data['course_id'] = $course->id;
        $data['is_free'] = $request->boolean('is_free');
        Lesson::create($data);
        return redirect()->route('admin.courses.show', $course)->with('success', 'Lesson created.');
    }

    public function show(Course $course, Lesson $lesson)
    {
        $lesson->load(['quizzes', 'course.lessons']);
        return view('admin.lessons.show', compact('course', 'lesson'));
    }

    public function edit(Course $course, Lesson $lesson)
    {
        return view('admin.lessons.edit', compact('course', 'lesson'));
    }

    public function update(Request $request, Course $course, Lesson $lesson)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'title_ar'         => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            'content'          => 'nullable|string',
            'content_ar'       => 'nullable|string',
            'video_url'        => 'nullable|url',
            'attachment'       => 'nullable|file|max:20480',
            'duration_minutes' => 'integer|min:0',
            'order'            => 'integer|min:0',
            'is_free'          => 'boolean',
            'status'           => 'in:draft,published',
        ]);
        if ($request->hasFile('attachment')) {
            if ($lesson->attachment) Storage::disk('public')->delete($lesson->attachment);
            $data['attachment'] = $request->file('attachment')->store('lessons/attachments', 'public');
        }
        $data['is_free'] = $request->boolean('is_free');
        $lesson->update($data);
        return redirect()->route('admin.courses.show', $course)->with('success', 'Lesson updated.');
    }

    public function destroy(Course $course, Lesson $lesson)
    {
        if ($lesson->attachment) Storage::disk('public')->delete($lesson->attachment);
        $lesson->delete();
        return redirect()->route('admin.courses.show', $course)->with('success', 'Lesson deleted.');
    }
}
