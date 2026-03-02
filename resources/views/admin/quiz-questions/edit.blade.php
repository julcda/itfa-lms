@extends('layouts.admin')
@section('title', __('messages.edit_question'))
@section('page-title', __('messages.edit_question'))

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="mb-4 pb-4 border-b border-gray-100">
            <p class="text-sm text-gray-500">{{ __('messages.quiz') }}: <span class="font-semibold text-gray-800">{{ $quiz->title_localized }}</span></p>
        </div>
        <form method="POST" action="{{ route('admin.quiz-questions.update', [$quiz, $question]) }}" class="space-y-5">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.question_en') }} *</label>
                    <textarea name="question" rows="3" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">{{ old('question', $question->question) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.question_ar') }}</label>
                    <textarea name="question_ar" rows="3" dir="rtl" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">{{ old('question_ar', $question->question_ar) }}</textarea>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.type') }}</label>
                    <select name="type" id="q_type" onchange="toggleOptions(this.value)" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        @foreach(['mcq','true_false','short_answer'] as $t)
                            <option value="{{ $t }}" {{ old('type',$question->type)===$t ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$t)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.points') }}</label>
                    <input type="number" name="points" value="{{ old('points', $question->points) }}" min="1" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.order') }}</label>
                    <input type="number" name="order" value="{{ old('order', $question->order) }}" min="1" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
            </div>
            <div id="mcq_options">
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.options') }}</label>
                @php $opts = $question->options ?? []; @endphp
                @foreach(['A','B','C','D'] as $i => $opt)
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-6 h-6 bg-gray-100 rounded text-xs font-bold flex items-center justify-center text-gray-500">{{ $opt }}</span>
                    <input type="text" name="options[]" value="{{ old('options.'.$i, $opts[$i] ?? '') }}" class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                @endforeach
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.correct_answer') }} *</label>
                <input type="text" name="correct_answer" value="{{ old('correct_answer', $question->correct_answer) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition">{{ __('messages.update') }}</button>
                <a href="{{ route('admin.quizzes.show', $quiz) }}" class="border border-gray-200 text-gray-600 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">{{ __('messages.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
function toggleOptions(type) {
    document.getElementById('mcq_options').classList.toggle('hidden', type !== 'mcq');
}
toggleOptions('{{ old('type', $question->type) }}');
</script>
@endpush
