@extends('layouts.lms')
@section('title', $quiz->title ?? __('messages.quiz'))

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="mb-4">
        <a href="{{ route('student.courses.show', $course) }}" class="text-sm text-emerald-600 hover:underline flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            {{ $course->title_localized }}
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Header --}}
        <div class="bg-emerald-600 px-6 py-5">
            <h1 class="text-xl font-bold text-white">{{ $quiz->title ?? __('messages.quiz') }}</h1>
            @if($quiz->lesson)<p class="text-emerald-200 text-sm">{{ __('messages.for_lesson') }}: {{ $quiz->lesson->title_localized }}</p>@endif
        </div>

        <div class="p-6">
            {{-- Quiz info grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                @foreach([
                    ['icon' => '❓', 'label' => 'messages.questions', 'value' => $quiz->questions->count()],
                    ['icon' => '🎯', 'label' => 'messages.passing_score', 'value' => $quiz->passing_score . '%'],
                    ['icon' => '🔄', 'label' => 'messages.attempts_allowed', 'value' => $quiz->max_attempts ?? __('messages.unlimited')],
                    ['icon' => '✅', 'label' => 'messages.your_attempts', 'value' => $attemptsCount],
                ] as $info)
                <div class="bg-emerald-50 rounded-lg p-3 text-center">
                    <div class="text-2xl mb-1">{{ $info['icon'] }}</div>
                    <div class="text-xs text-gray-500">{{ __($info['label']) }}</div>
                    <div class="font-bold text-gray-800 text-sm">{{ $info['value'] }}</div>
                </div>
                @endforeach
            </div>

            @if($quiz->description)<p class="text-gray-600 text-sm mb-6 leading-relaxed">{{ $quiz->description }}</p>@endif

            {{-- Last attempt summary --}}
            @if($lastAttempt)
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-semibold text-gray-700 text-sm">{{ __('messages.last_attempt') }}</span>
                    @if($lastAttempt->passed)
                    <span class="bg-emerald-100 text-emerald-700 text-xs px-2 py-0.5 rounded-full font-medium">{{ __('messages.passed') }}</span>
                    @else
                    <span class="bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded-full font-medium">{{ __('messages.failed') }}</span>
                    @endif
                </div>
                <div class="flex items-center gap-4 text-sm text-gray-600">
                    <span>{{ __('messages.score') }}: <strong>{{ $lastAttempt->score }}%</strong></span>
                    <span>{{ \Carbon\Carbon::parse($lastAttempt->created_at)->format('d M Y') }}</span>
                </div>
                <a href="{{ route('student.quizzes.result', $lastAttempt) }}" class="text-xs text-emerald-600 hover:underline mt-1 inline-block">{{ __('messages.view_results') }}</a>
            </div>
            @endif

            {{-- Start button --}}
            @php $canAttempt = !$quiz->max_attempts || $attemptsCount < $quiz->max_attempts; @endphp
            @if($canAttempt)
            <form method="POST" action="{{ route('student.quizzes.start', $quiz) }}">
                @csrf
                <button type="submit" class="w-full bg-emerald-600 text-white py-3 rounded-xl font-semibold hover:bg-emerald-700 transition text-sm flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $attemptsCount > 0 ? __('messages.retake_quiz') : __('messages.start_quiz') }}
                </button>
            </form>
            @else
            <div class="w-full bg-gray-100 text-gray-500 py-3 rounded-xl text-center text-sm font-medium">
                {{ __('messages.max_attempts_reached') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
