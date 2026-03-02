@extends('layouts.admin')
@section('title', __('messages.add_question'))
@section('page-title', __('messages.add_question'))

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="mb-4 pb-4 border-b border-gray-100">
            <p class="text-sm text-gray-500">{{ __('messages.quiz') }}: <span class="font-semibold text-gray-800">{{ $quiz->title_localized }}</span></p>
        </div>
        <form method="POST" action="{{ route('admin.quiz-questions.store', $quiz) }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.question_en') }} *</label>
                    <textarea name="question" rows="3" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">{{ old('question') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.question_ar') }}</label>
                    <textarea name="question_ar" rows="3" dir="rtl" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">{{ old('question_ar') }}</textarea>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.type') }} *</label>
                    <select name="type" id="q_type" onchange="toggleOptions(this.value)" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        <option value="mcq" {{ old('type')==='mcq' ? 'selected' : '' }}>MCQ</option>
                        <option value="true_false" {{ old('type')==='true_false' ? 'selected' : '' }}>True / False</option>
                        <option value="short_answer" {{ old('type')==='short_answer' ? 'selected' : '' }}>Short Answer</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.points') }}</label>
                    <input type="number" name="points" value="{{ old('points', 1) }}" min="1" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.order') }}</label>
                    <input type="number" name="order" value="{{ old('order', 1) }}" min="1" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
            </div>

            <div id="mcq_options">
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.options') }} *</label>
                <div class="space-y-2">
                    @foreach(['A','B','C','D'] as $opt)
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 bg-gray-100 rounded text-xs font-bold flex items-center justify-center text-gray-500">{{ $opt }}</span>
                        <input type="text" name="options[]" value="{{ old('options.'.(ord($opt)-65)) }}" placeholder="{{ __('messages.option') }} {{ $opt }}" class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    </div>
                    @endforeach
                </div>
            </div>

            <div id="tf_options" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.options') }}</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2"><input type="radio" name="tf_val" value="True" class="text-emerald-600"> True</label>
                    <label class="flex items-center gap-2"><input type="radio" name="tf_val" value="False" class="text-emerald-600"> False</label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.correct_answer') }} *</label>
                <input type="text" name="correct_answer" value="{{ old('correct_answer') }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                <p class="text-xs text-gray-400 mt-1">{{ __('messages.correct_answer_hint') }}</p>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition">{{ __('messages.save') }}</button>
                <button type="submit" name="add_another" value="1" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">{{ __('messages.save_add_another') }}</button>
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
    document.getElementById('tf_options').classList.toggle('hidden', type !== 'true_false');
}
toggleOptions('{{ old('type','mcq') }}');
</script>
@endpush
