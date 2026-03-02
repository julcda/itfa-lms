<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;

class QuizQuestionController extends Controller
{
    public function index(Quiz $quiz)
    {
        $questions = $quiz->questions;
        return view('admin.quiz-questions.index', compact('quiz', 'questions'));
    }

    public function create(Quiz $quiz)
    {
        return view('admin.quiz-questions.create', compact('quiz'));
    }

    public function store(Request $request, Quiz $quiz)
    {
        $data = $request->validate([
            'question_text'    => 'required|string',
            'question_text_ar' => 'nullable|string',
            'type'             => 'required|in:multiple_choice,true_false,short_answer',
            'options'          => 'nullable|array',
            'options.*'        => 'string',
            'correct_answer'   => 'required|string',
            'explanation'      => 'nullable|string',
            'explanation_ar'   => 'nullable|string',
            'points'           => 'integer|min:1',
            'order'            => 'integer|min:0',
        ]);
        $data['quiz_id'] = $quiz->id;
        QuizQuestion::create($data);
        return redirect()->route('admin.quizzes.questions.index', $quiz)->with('success', 'Question added.');
    }

    public function show(Quiz $quiz, QuizQuestion $question)
    {
        return view('admin.quiz-questions.show', compact('quiz', 'question'));
    }

    public function edit(Quiz $quiz, QuizQuestion $question)
    {
        return view('admin.quiz-questions.edit', compact('quiz', 'question'));
    }

    public function update(Request $request, Quiz $quiz, QuizQuestion $question)
    {
        $data = $request->validate([
            'question_text'    => 'required|string',
            'question_text_ar' => 'nullable|string',
            'type'             => 'required|in:multiple_choice,true_false,short_answer',
            'options'          => 'nullable|array',
            'options.*'        => 'string',
            'correct_answer'   => 'required|string',
            'explanation'      => 'nullable|string',
            'points'           => 'integer|min:1',
            'order'            => 'integer|min:0',
        ]);
        $question->update($data);
        return redirect()->route('admin.quizzes.questions.index', $quiz)->with('success', 'Question updated.');
    }

    public function destroy(Quiz $quiz, QuizQuestion $question)
    {
        $question->delete();
        return redirect()->route('admin.quizzes.questions.index', $quiz)->with('success', 'Question deleted.');
    }
}
