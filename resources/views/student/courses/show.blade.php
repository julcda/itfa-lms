@extends('layouts.lms')
@section('title', $course->title_localized)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    {{-- Back --}}
    <div class="mb-4">
        <a href="{{ route('student.courses.index') }}" class="text-sm text-emerald-600 hover:underline flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            {{ __('messages.back_to_my_courses') }}
        </a>
    </div>

    {{-- Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="h-48 md:h-64 bg-emerald-50 relative">
            @if($course->thumbnail)
            <img src="{{ $course->thumbnail_url }}" class="w-full h-full object-cover">
            @else
            <div class="w-full h-full flex items-center justify-center text-6xl">📘</div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            <div class="absolute bottom-4 start-4 end-4">
                <h1 class="text-xl md:text-2xl font-bold text-white mb-1">{{ $course->title_localized }}</h1>
                <div class="flex items-center gap-3 text-emerald-200 text-sm">
                    <span>{{ $course->teacher->name ?? '-' }}</span>
                    <span>•</span>
                    <span class="capitalize">{{ $course->level }}</span>
                    <span>•</span>
                    <span>{{ $lessons->count() }} {{ __('messages.lessons') }}</span>
                </div>
            </div>
        </div>
        <div class="p-4 border-t border-gray-100">
            @php $pct = $enrollment->progress ?? 0; @endphp
            <div class="flex items-center gap-3">
                <div class="flex-1 h-2 bg-gray-100 rounded-full">
                    <div class="h-2 bg-emerald-500 rounded-full" style="width:{{ $pct }}%"></div>
                </div>
                <span class="text-sm font-semibold text-emerald-700">{{ $pct }}% {{ __('messages.complete') }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Lessons list --}}
        <div class="lg:col-span-2">
            <h2 class="font-bold text-gray-800 mb-4">{{ __('messages.course_content') }}</h2>
            <div class="space-y-2">
                @forelse($lessons as $i => $lesson)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center shrink-0 text-sm font-bold text-emerald-700">
                            {{ $lesson->order ?? $i + 1 }}
                        </div>
                        <div>
                            <div class="font-medium text-gray-800 text-sm">{{ $lesson->title_localized }}</div>
                            <div class="text-xs text-gray-400 capitalize flex items-center gap-1 mt-0.5">
                                @if($lesson->type === 'video')🎬
                                @elseif($lesson->type === 'document')📄
                                @else📝@endif
                                {{ $lesson->type }}
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('student.courses.lesson', [$course, $lesson]) }}" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs px-3 py-1.5 rounded-lg transition font-medium">
                        {{ __('messages.open') }}
                    </a>
                </div>
                @empty
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center text-gray-400">{{ __('messages.no_lessons_yet') }}</div>
                @endforelse
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <h3 class="font-semibold text-gray-800 mb-3">{{ __('messages.course_info') }}</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">{{ __('messages.level') }}</span><span class="capitalize font-medium">{{ $course->level }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">{{ __('messages.instructor') }}</span><span class="font-medium">{{ $course->teacher->name ?? '-' }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">{{ __('messages.lessons') }}</span><span class="font-medium">{{ $lessons->count() }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">{{ __('messages.enrolled') }}</span><span class="font-medium">{{ \Carbon\Carbon::parse($enrollment->created_at)->format('d M Y') }}</span></div>
                </div>
            </div>

            @if($course->description)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <h3 class="font-semibold text-gray-800 mb-2">{{ __('messages.description') }}</h3>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $course->description_localized ?? $course->description }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
