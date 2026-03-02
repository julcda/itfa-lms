@extends('layouts.admin')
@section('title', __('messages.edit_lesson'))
@section('page-title', __('messages.edit_lesson'))

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="mb-4 pb-4 border-b border-gray-100">
            <p class="text-sm text-gray-500">{{ __('messages.course') }}: <span class="font-semibold text-gray-800">{{ $course->title_localized }}</span></p>
        </div>
        <form method="POST" action="{{ route('admin.courses.lessons.update', [$course, $lesson]) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.title_en') }} *</label>
                    <input type="text" name="title" value="{{ old('title', $lesson->title) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.title_ar') }}</label>
                    <input type="text" name="title_ar" value="{{ old('title_ar', $lesson->title_ar) }}" dir="rtl" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.type') }}</label>
                    <select name="type" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        @foreach(['text','video','document'] as $t)
                            <option value="{{ $t }}" {{ old('type',$lesson->type)===$t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.order') }}</label>
                    <input type="number" name="order" value="{{ old('order', $lesson->order) }}" min="1" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.video_url') }}</label>
                <input type="url" name="video_url" value="{{ old('video_url', $lesson->video_url) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.content_en') }}</label>
                    <textarea name="content" rows="5" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">{{ old('content', $lesson->content) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.content_ar') }}</label>
                    <textarea name="content_ar" rows="5" dir="rtl" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">{{ old('content_ar', $lesson->content_ar) }}</textarea>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.attachment') }}</label>
                @if($lesson->attachment)<p class="text-xs text-gray-400 mb-1">{{ __('messages.current') }}: {{ basename($lesson->attachment) }}</p>@endif
                <input type="file" name="attachment" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition">{{ __('messages.update') }}</button>
                <a href="{{ route('admin.courses.show', $course) }}" class="border border-gray-200 text-gray-600 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">{{ __('messages.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
