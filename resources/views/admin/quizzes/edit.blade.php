@extends('layouts.admin')
@section('title', __('messages.edit_quiz'))
@section('page-title', __('messages.edit_quiz'))

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.quizzes.update', $quiz) }}" class="space-y-5">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.title_en') }} *</label>
                    <input type="text" name="title" value="{{ old('title', $quiz->title) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.title_ar') }}</label>
                    <input type="text" name="title_ar" value="{{ old('title_ar', $quiz->title_ar) }}" dir="rtl" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.description') }}</label>
                <textarea name="description" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">{{ old('description', $quiz->description) }}</textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.course') }}</label>
                    <select name="course_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id',$quiz->course_id)==$course->id ? 'selected' : '' }}>{{ $course->title_localized }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.lesson') }}</label>
                    <select name="lesson_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        <option value="">-</option>
                        @foreach($lessons as $lesson)
                            <option value="{{ $lesson->id }}" {{ old('lesson_id',$quiz->lesson_id)==$lesson->id ? 'selected' : '' }}>{{ $lesson->title_localized }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.passing_score') }} %</label>
                    <input type="number" name="passing_score" value="{{ old('passing_score', $quiz->passing_score) }}" min="0" max="100" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.max_attempts') }}</label>
                    <input type="number" name="max_attempts" value="{{ old('max_attempts', $quiz->max_attempts) }}" min="1" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.duration_minutes') }}</label>
                    <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $quiz->duration_minutes) }}" min="1" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_randomized" id="is_randomized" value="1" {{ old('is_randomized', $quiz->is_randomized) ? 'checked' : '' }} class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                <label for="is_randomized" class="text-sm text-gray-700">{{ __('messages.randomize_questions') }}</label>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition">{{ __('messages.update') }}</button>
                <a href="{{ route('admin.quizzes.show', $quiz) }}" class="border border-gray-200 text-gray-600 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">{{ __('messages.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
