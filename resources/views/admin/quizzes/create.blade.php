@extends('layouts.admin')
@section('title', __('messages.add_quiz'))
@section('page-title', __('messages.add_quiz'))

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.quizzes.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.title_en') }} *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.title_ar') }}</label>
                    <input type="text" name="title_ar" value="{{ old('title_ar') }}" dir="rtl" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.description') }}</label>
                <textarea name="description" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">{{ old('description') }}</textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.course') }} *</label>
                    <select name="course_id" required id="course_sel" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        <option value="">{{ __('messages.select_course') }}</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id')==$course->id ? 'selected' : '' }}>{{ $course->title_localized }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.lesson') }}</label>
                    <select name="lesson_id" id="lesson_sel" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        <option value="">-</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.passing_score') }} %</label>
                    <input type="number" name="passing_score" value="{{ old('passing_score', 60) }}" min="0" max="100" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.max_attempts') }}</label>
                    <input type="number" name="max_attempts" value="{{ old('max_attempts') }}" min="1" placeholder="{{ __('messages.unlimited') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.duration_minutes') }}</label>
                    <input type="number" name="duration_minutes" value="{{ old('duration_minutes') }}" min="1" placeholder="{{ __('messages.no_limit') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_randomized" id="is_randomized" value="1" {{ old('is_randomized') ? 'checked' : '' }} class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                <label for="is_randomized" class="text-sm text-gray-700">{{ __('messages.randomize_questions') }}</label>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition">{{ __('messages.save') }}</button>
                <a href="{{ route('admin.quizzes.index') }}" class="border border-gray-200 text-gray-600 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">{{ __('messages.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const lessonsData = @json($lessons ?? []);
document.getElementById('course_sel').addEventListener('change', function() {
    const sel = document.getElementById('lesson_sel');
    sel.innerHTML = '<option value="">-</option>';
    const lessons = (lessonsData[this.value] || []);
    lessons.forEach(l => {
        const opt = document.createElement('option');
        opt.value = l.id; opt.textContent = l.title;
        sel.appendChild(opt);
    });
});
</script>
@endpush
