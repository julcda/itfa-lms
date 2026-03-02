<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function show(Quiz $quiz)
    {
        $quiz->load('course', 'questions');
        $user = auth()->user();
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $quiz->course_id)->first();
        abort_unless($enrollment, 403);
        $attempts = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)->count();
        $canAttempt = $attempts < $quiz->max_attempts;
        return view('student.quizzes.show', compact('quiz', 'attempts', 'canAttempt'));
    }

    public function start(Quiz $quiz)
    {
        $user = auth()->user();
        $attempts = QuizAttempt::where('user_id', $user->id)->where('quiz_id', $quiz->id)->count();
        abort_if($attempts >= $quiz->max_attempts, 403, 'Max attempts reached.');
        $attempt = QuizAttempt::create([
            'user_id'    => $user->id,
            'quiz_id'    => $quiz->id,
            'started_at' => now(),
        ]);
        $questions = $quiz->is_randomized ? $quiz->questions->shuffle() : $quiz->questions;
        return view('student.quizzes.take', compact('quiz', 'attempt', 'questions'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $attempt = QuizAttempt::where('user_id', auth()->id())
            ->where('quiz_id', $quiz->id)
            ->whereNull('completed_at')
            ->latest()->firstOrFail();

        $answers = $request->input('answers', []);
        $questions = $quiz->questions;
        $totalPoints = $questions->sum('points');
        $earned = 0;

        foreach ($questions as $q) {
            $given = $answers[$q->id] ?? null;
            if ($given !== null && strtolower(trim($given)) === strtolower(trim($q->correct_answer))) {
                $earned += $q->points;
            }
        }

        $score = $totalPoints > 0 ? ($earned / $totalPoints) * 100 : 0;
        $passed = $score >= $quiz->passing_score;

        $attempt->update([
            'answers'      => $answers,
            'score'        => $score,
            'passed'       => $passed,
            'completed_at' => now(),
        ]);

        return redirect()->route('student.quizzes.result', [$quiz, $attempt]);
    }

    public function result(Quiz $quiz, QuizAttempt $attempt)
    {
        abort_if($attempt->user_id !== auth()->id(), 403);
        $attempt->load('quiz.questions');
        return view('student.quizzes.result', compact('quiz', 'attempt'));
    }
}
