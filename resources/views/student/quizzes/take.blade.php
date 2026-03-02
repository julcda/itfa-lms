@extends('layouts.lms')
@section('title', __('messages.take_quiz'))

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    {{-- Quiz header bar --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6 flex items-center justify-between">
        <div>
            <h1 class="font-bold text-gray-800">{{ $attempt->quiz->title ?? __('messages.quiz') }}</h1>
            <p class="text-xs text-gray-400">{{ $attempt->quiz->questions->count() }} {{ __('messages.questions') }}</p>
        </div>
        @if($attempt->quiz->duration)
        <div id="quiz-timer" class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-2 rounded-lg font-mono text-sm font-bold">
            {{ sprintf('%02d:%02d', intdiv($attempt->quiz->duration, 60), $attempt->quiz->duration % 60) }}
        </div>
        @endif
    </div>

    <form method="POST" action="{{ route('student.quizzes.submit', $attempt) }}" id="quizForm">
        @csrf

        @foreach($attempt->quiz->questions as $idx => $question)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-4">
            <div class="flex items-start gap-3 mb-4">
                <span class="shrink-0 w-7 h-7 bg-emerald-600 text-white rounded-full flex items-center justify-center text-xs font-bold">{{ $idx + 1 }}</span>
                <div class="flex-1">
                    <p class="font-medium text-gray-800">{{ $question->question }}</p>
                    @if($question->question_ar)<p class="text-gray-600 text-sm mt-1 font-arabic" dir="rtl">{{ $question->question_ar }}</p>@endif
                </div>
            </div>

            @if($question->type === 'mcq')
            <div class="space-y-2 ms-10">
                @foreach($question->options ?? [] as $optIdx => $option)
                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 cursor-pointer hover:bg-emerald-50 hover:border-emerald-200 transition has-[:checked]:bg-emerald-50 has-[:checked]:border-emerald-400">
                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $optIdx }}" class="text-emerald-600 focus:ring-emerald-500">
                    <span class="text-sm text-gray-700">{{ $option }}</span>
                </label>
                @endforeach
            </div>

            @elseif($question->type === 'true_false')
            <div class="flex gap-3 ms-10">
                @foreach(['true' => __('messages.true'), 'false' => __('messages.false')] as $val => $label)
                <label class="flex-1 flex items-center justify-center gap-2 p-3 rounded-lg border border-gray-100 cursor-pointer hover:bg-emerald-50 hover:border-emerald-200 transition has-[:checked]:bg-emerald-50 has-[:checked]:border-emerald-400">
                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $val }}" class="text-emerald-600 focus:ring-emerald-500">
                    <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                </label>
                @endforeach
            </div>

            @elseif($question->type === 'short_answer')
            <div class="ms-10">
                <textarea name="answers[{{ $question->id }}]" rows="3" placeholder="{{ __('messages.your_answer') }}"
                    class="w-full border border-gray-200 rounded-lg p-3 text-sm focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 resize-none"></textarea>
            </div>
            @endif
        </div>
        @endforeach

        <div class="flex items-center justify-between mt-6">
            <span class="text-sm text-gray-400">{{ __('messages.answer_all_questions') }}</span>
            <button type="submit" onclick="return confirm('{{ __('messages.submit_quiz_confirm') }}')"
                class="bg-emerald-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-emerald-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ __('messages.submit_quiz') }}
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
@if($attempt->quiz->duration)
<script>
(function(){
    const totalSeconds = {{ $attempt->quiz->duration * 60 }};
    let remaining = totalSeconds;
    const el = document.getElementById('quiz-timer');
    const interval = setInterval(() => {
        remaining--;
        const m = Math.floor(remaining / 60).toString().padStart(2,'0');
        const s = (remaining % 60).toString().padStart(2,'0');
        el.textContent = m + ':' + s;
        if(remaining <= 60) el.classList.add('text-red-600','border-red-300','bg-red-50');
        if(remaining <= 0){ clearInterval(interval); document.getElementById('quizForm').submit(); }
    }, 1000);
})();
</script>
@endif
@endpush
