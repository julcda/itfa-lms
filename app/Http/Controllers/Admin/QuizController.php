<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $quizzes = Quiz::with('course', 'lesson')->latest()->paginate(15);
        return view('admin.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $courses = Course::where('status', 'published')->with('lessons')->get();
        return view('admin.quizzes.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id'        => 'required|exists:courses,id',
            'lesson_id'        => 'nullable|exists:lessons,id',
            'title'            => 'required|string|max:255',
            'title_ar'         => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            'duration_minutes' => 'integer|min:1',
            'passing_score'    => 'integer|min:0|max:100',
            'max_attempts'     => 'integer|min:1',
            'is_randomized'    => 'boolean',
            'status'           => 'in:draft,published,archived',
        ]);
        $data['is_randomized'] = $request->boolean('is_randomized');
        Quiz::create($data);
        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz created.');
    }

    public function show(Quiz $quiz)
    {
        $quiz->load('course', 'questions', 'attempts.user');
        return view('admin.quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        $courses = Course::where('status', 'published')->with('lessons')->get();
        return view('admin.quizzes.edit', compact('quiz', 'courses'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $data = $request->validate([
            'course_id'        => 'required|exists:courses,id',
            'lesson_id'        => 'nullable|exists:lessons,id',
            'title'            => 'required|string|max:255',
            'title_ar'         => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            'duration_minutes' => 'integer|min:1',
            'passing_score'    => 'integer|min:0|max:100',
            'max_attempts'     => 'integer|min:1',
            'is_randomized'    => 'boolean',
            'status'           => 'in:draft,published,archived',
        ]);
        $data['is_randomized'] = $request->boolean('is_randomized');
        $quiz->update($data);
        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz updated.');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz deleted.');
    }
}
