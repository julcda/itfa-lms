@extends('layouts.lms')
@section('title', __('messages.quiz_result'))

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    @php $quiz = $attempt->quiz; @endphp

    {{-- Score banner --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="{{ $attempt->passed ? 'bg-emerald-600' : 'bg-red-500' }} px-6 py-6 text-center">
            <div class="text-4xl mb-2">{{ $attempt->passed ? '🎉' : '😞' }}</div>
            <h1 class="text-xl font-bold text-white">{{ $attempt->passed ? __('messages.congratulations') : __('messages.better_luck') }}</h1>
            <p class="text-{{ $attempt->passed ? 'emerald' : 'red' }}-100 text-sm mt-1">{{ $quiz->title }}</p>
        </div>
        <div class="p-6">
            <div class="flex items-center justify-center gap-8">
                <div class="text-center">
                    <div class="text-4xl font-bold {{ $attempt->passed ? 'text-emerald-600' : 'text-red-500' }}">{{ number_format($attempt->score, 1) }}%</div>
                    <div class="text-sm text-gray-400 mt-1">{{ __('messages.your_score') }}</div>
                </div>
                <div class="w-px h-12 bg-gray-200"></div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-gray-600">{{ $quiz->passing_score }}%</div>
                    <div class="text-sm text-gray-400 mt-1">{{ __('messages.passing_score') }}</div>
                </div>
                <div class="w-px h-12 bg-gray-200"></div>
                <div class="text-center">
                    <div class="text-2xl font-bold {{ $attempt->passed ? 'text-emerald-600' : 'text-red-500' }}">
                        {{ $attempt->passed ? __('messages.passed') : __('messages.failed') }}
                    </div>
                    <div class="text-sm text-gray-400 mt-1">{{ __('messages.status') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Question review --}}
    <h2 class="font-bold text-gray-800 mb-4">{{ __('messages.question_review') }}</h2>
    <div class="space-y-4">
        @foreach($quiz->questions as $idx => $question)
        @php
            $studentAnswer = $attempt->answers[$question->id] ?? null;
            $isCorrect = false;
            if($question->type === 'short_answer') {
                $isCorrect = null; // manual grading
            } elseif($question->type === 'true_false') {
                $isCorrect = strtolower($studentAnswer ?? '') === strtolower($question->correct_answer ?? '');
            } else {
                $isCorrect = (string)$studentAnswer === (string)$question->correct_answer;
            }
        @endphp
        <div class="bg-white rounded-xl shadow-sm border {{ $isCorrect === true ? 'border-emerald-200' : ($isCorrect === false ? 'border-red-200' : 'border-gray-100') }} p-5">
            <div class="flex items-start gap-3 mb-3">
                <span class="shrink-0 w-7 h-7 {{ $isCorrect === true ? 'bg-emerald-100 text-emerald-700' : ($isCorrect === false ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600') }} rounded-full flex items-center justify-center text-xs font-bold">{{ $idx + 1 }}</span>
                <div>
                    <p class="font-medium text-gray-800">{{ $question->question }}</p>
                    @if($question->question_ar)<p class="text-gray-600 text-sm mt-0.5 font-arabic" dir="rtl">{{ $question->question_ar }}</p>@endif
                </div>
                @if($isCorrect !== null)
                <span class="ms-auto shrink-0 text-lg">{{ $isCorrect ? '✅' : '❌' }}</span>
                @endif
            </div>

            @if($question->type === 'mcq')
            <div class="ms-10 space-y-1">
                @foreach($question->options ?? [] as $optIdx => $option)
                <div class="text-sm px-3 py-1.5 rounded-lg
                    {{ (string)$optIdx === (string)$question->correct_answer ? 'bg-emerald-50 text-emerald-700 font-medium border border-emerald-200' : '' }}
                    {{ (string)$optIdx === (string)$studentAnswer && (string)$optIdx !== (string)$question->correct_answer ? 'bg-red-50 text-red-700 border border-red-200' : '' }}">
                    @if((string)$optIdx === (string)$question->correct_answer)✓ @elseif((string)$optIdx === (string)$studentAnswer)✗ @endif
                    {{ $option }}
                </div>
                @endforeach
            </div>

            @elseif($question->type === 'true_false')
            <div class="ms-10 flex gap-3">
                @foreach(['true' => __('messages.true'), 'false' => __('messages.false')] as $val => $label)
                <span class="text-sm px-3 py-1 rounded-lg border
                    {{ $val === $question->correct_answer ? 'bg-emerald-50 text-emerald-700 border-emerald-200 font-medium' : 'border-gray-100 text-gray-500' }}
                    {{ $val === $studentAnswer && $val !== $question->correct_answer ? 'bg-red-50 text-red-700 border-red-200' : '' }}">
                    {{ $label }}
                </span>
                @endforeach
            </div>

            @elseif($question->type === 'short_answer')
            <div class="ms-10">
                <div class="text-xs text-gray-400 mb-1">{{ __('messages.your_answer') }}:</div>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-sm text-gray-700">{{ $studentAnswer ?? '-' }}</div>
                @if($question->correct_answer)<div class="mt-2 text-xs text-gray-400">{{ __('messages.model_answer') }}: <span class="text-emerald-700 font-medium">{{ $question->correct_answer }}</span></div>@endif
            </div>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Actions --}}
    <div class="mt-6 flex flex-wrap gap-3">
        <a href="{{ route('student.courses.show', $quiz->course ?? $quiz->lesson->course ?? '#') }}" class="bg-white border border-gray-200 text-gray-700 px-5 py-2 rounded-lg hover:border-emerald-400 hover:text-emerald-700 transition text-sm">{{ __('messages.back_to_course') }}</a>
        @if($attempt->passed && $quiz->course && $quiz->course->certificate)
        <a href="{{ route('student.certificates.index') }}" class="bg-emerald-600 text-white px-5 py-2 rounded-lg hover:bg-emerald-700 transition text-sm">{{ __('messages.view_certificate') }}</a>
        @endif
    </div>
</div>
@endsection
