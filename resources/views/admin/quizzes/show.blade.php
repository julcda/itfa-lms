@extends('layouts.admin')
@section('title', $quiz->title_localized)
@section('page-title', $quiz->title_localized)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-4">
        {{-- Quiz info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="font-bold text-gray-800">{{ $quiz->title }}</h2>
                    @if($quiz->title_ar)<p class="text-sm text-gray-500" dir="rtl">{{ $quiz->title_ar }}</p>@endif
                    @if($quiz->description)<p class="text-sm text-gray-600 mt-1">{{ $quiz->description }}</p>@endif
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-blue-700 transition">{{ __('messages.edit') }}</a>
                    <a href="{{ route('admin.quiz-questions.create', $quiz) }}" class="bg-emerald-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-emerald-700 transition">+ {{ __('messages.add_question') }}</a>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 text-center p-4 bg-gray-50 rounded-lg">
                <div><div class="text-xl font-bold text-emerald-700">{{ $quiz->questions->count() }}</div><div class="text-xs text-gray-400">{{ __('messages.questions') }}</div></div>
                <div><div class="text-xl font-bold text-blue-700">{{ $quiz->passing_score }}%</div><div class="text-xs text-gray-400">{{ __('messages.passing_score') }}</div></div>
                <div><div class="text-xl font-bold text-purple-700">{{ $quiz->attempts->count() }}</div><div class="text-xs text-gray-400">{{ __('messages.attempts') }}</div></div>
            </div>
        </div>

        {{-- Questions list --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between p-5 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">{{ __('messages.questions') }}</h3>
                <a href="{{ route('admin.quiz-questions.create', $quiz) }}" class="text-emerald-600 hover:text-emerald-700 text-sm">+ {{ __('messages.add') }}</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($quiz->questions()->orderBy('order')->get() as $i => $q)
                <div class="px-5 py-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-3 flex-1">
                            <span class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-xs font-bold text-gray-500 shrink-0 mt-0.5">{{ $i+1 }}</span>
                            <div>
                                <p class="text-sm text-gray-800">{{ $q->question_localized }}</p>
                                <span class="text-xs text-gray-400 capitalize mt-0.5">{{ $q->type }} · {{ $q->points }} {{ __('messages.pts') }}</span>
                                @if(in_array($q->type, ['mcq','true_false']))
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach(($q->options ?? []) as $opt)
                                            <span class="px-2 py-0.5 text-xs rounded-full {{ $opt === $q->correct_answer ? 'bg-green-100 text-green-700 font-semibold' : 'bg-gray-100 text-gray-500' }}">{{ $opt }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="flex gap-2 shrink-0">
                            <a href="{{ route('admin.quiz-questions.edit', [$quiz, $q]) }}" class="text-xs text-blue-500 hover:text-blue-700">{{ __('messages.edit') }}</a>
                            <form method="POST" action="{{ route('admin.quiz-questions.destroy', [$quiz, $q]) }}" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-600">{{ __('messages.delete') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-5 py-6 text-center text-gray-400 text-sm">{{ __('messages.no_questions_yet') }}</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">{{ __('messages.quiz_settings') }}</h3>
            <dl class="space-y-3 text-sm">
                <div><dt class="text-gray-400 text-xs">{{ __('messages.course') }}</dt><dd class="mt-0.5 font-medium">{{ $quiz->course->title_localized ?? '-' }}</dd></div>
                <div><dt class="text-gray-400 text-xs">{{ __('messages.lesson') }}</dt><dd class="mt-0.5">{{ $quiz->lesson->title_localized ?? '-' }}</dd></div>
                <div><dt class="text-gray-400 text-xs">{{ __('messages.max_attempts') }}</dt><dd class="mt-0.5">{{ $quiz->max_attempts ?? __('messages.unlimited') }}</dd></div>
                <div><dt class="text-gray-400 text-xs">{{ __('messages.random') }}</dt><dd class="mt-0.5">{{ $quiz->is_randomized ? __('messages.yes') : __('messages.no') }}</dd></div>
            </dl>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-800 mb-3">{{ __('messages.recent_attempts') }}</h3>
            @forelse($quiz->attempts()->with('user')->latest()->take(5)->get() as $att)
                <div class="flex justify-between py-1.5 border-b border-gray-50 last:border-0 text-sm">
                    <span class="text-gray-700">{{ $att->user->name ?? '-' }}</span>
                    <span class="{{ $att->passed ? 'text-green-600' : 'text-red-500' }} font-semibold">{{ $att->score }}%</span>
                </div>
            @empty<p class="text-sm text-gray-400">{{ __('messages.no_data_yet') }}</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
