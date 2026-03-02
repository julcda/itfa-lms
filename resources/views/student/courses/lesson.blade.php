@extends('layouts.lms')
@section('title', $lesson->title_localized)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    {{-- Breadcrumb --}}
    <div class="mb-4 text-sm text-gray-500 flex items-center gap-1 flex-wrap">
        <a href="{{ route('student.courses.index') }}" class="hover:text-emerald-600">{{ __('messages.my_courses') }}</a>
        <span>/</span>
        <a href="{{ route('student.courses.show', $course) }}" class="hover:text-emerald-600">{{ $course->title_localized }}</a>
        <span>/</span>
        <span class="text-gray-700 font-medium">{{ $lesson->title_localized }}</span>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-4">
        {{-- Lesson header --}}
        <div class="bg-emerald-600 px-6 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold text-white">{{ $lesson->title_localized }}</h1>
                <div class="text-emerald-200 text-sm flex items-center gap-2 mt-0.5">
                    @if($lesson->video_url)<span>{{ __('messages.video') }}</span>@elseif($lesson->attachment)<span>{{ __('messages.file') }}</span>@endif
                    @if($lesson->order)<span>•</span><span>{{ __('messages.lesson') }} {{ $lesson->order }}</span>@endif
                    @if($lesson->duration_minutes)<span>•</span><span>{{ $lesson->duration_minutes }} {{ __('messages.minutes') }}</span>@endif
                </div>
            </div>
        </div>

        <div class="p-6">
            {{-- Video --}}
            @if($lesson->video_url)
            <div class="mb-6 rounded-lg overflow-hidden bg-black aspect-video">
                @php
                    $url = $lesson->video_url;
                    $embed = null;
                    if (str_contains($url, 'youtube.com/watch?v=')) {
                        $id = explode('v=', $url)[1];
                        $id = explode('&', $id)[0];
                        $embed = 'https://www.youtube.com/embed/'.$id.'?rel=0';
                    } elseif (str_contains($url, 'youtu.be/')) {
                        $id = explode('?', last(explode('/', $url)))[0];
                        $embed = 'https://www.youtube.com/embed/'.$id.'?rel=0';
                    } elseif (str_contains($url, 'youtube.com/embed/')) {
                        $embed = $url;
                    } elseif (str_contains($url, 'vimeo.com/')) {
                        $id = last(explode('/', rtrim($url, '/')));
                        $embed = 'https://player.vimeo.com/video/'.$id;
                    }
                @endphp
                @if($embed)
                <iframe src="{{ $embed }}" class="w-full h-full" allowfullscreen frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"></iframe>
                @else
                {{-- Direct video file --}}
                <video controls class="w-full h-full" preload="metadata">
                    <source src="{{ $url }}" type="video/mp4">
                    <source src="{{ $url }}" type="video/webm">
                    <source src="{{ $url }}" type="video/ogg">
                    Your browser does not support the video tag.
                </video>
                @endif
            </div>
            @endif

            {{-- Text content --}}
            @if($lesson->content)
            <div class="prose prose-emerald max-w-none text-gray-700 leading-relaxed mb-6">
                {!! nl2br(e($lesson->content)) !!}
            </div>
            @endif

            @if($lesson->content_ar)
            <div class="bg-emerald-50 border border-emerald-100 rounded-lg p-4 mb-6" dir="rtl" lang="ar">
                <div class="prose prose-emerald max-w-none text-gray-700 leading-relaxed font-arabic">
                    {!! nl2br(e($lesson->content_ar)) !!}
                </div>
            </div>
            @endif

            {{-- Attachment --}}
            @if($lesson->attachment)
            @php
                $attachPath = $lesson->attachment;
                $attachName = basename($attachPath);
                $ext        = strtolower(pathinfo($attachName, PATHINFO_EXTENSION));
                $isImage    = in_array($ext, ['jpg','jpeg','png','gif','webp','svg']);
                $isPdf      = $ext === 'pdf';
                $previewUrl = Storage::disk('public')->exists($attachPath) ? asset('storage/'.$attachPath) : null;
                $downloadUrl = route('student.courses.lessons.download', [$course, $lesson]);
            @endphp
            <div class="border border-dashed border-emerald-200 rounded-lg p-4 bg-emerald-50">
                <div class="flex items-center gap-3">
                    @if($isPdf)
                    <svg class="w-8 h-8 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    @else
                    <svg class="w-8 h-8 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-gray-700">{{ __('messages.lesson_attachment') }}</div>
                        <div class="text-xs text-gray-400 truncate">{{ $attachName }}</div>
                    </div>
                    <a href="{{ $downloadUrl }}"
                       class="shrink-0 bg-emerald-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-emerald-700 transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        {{ __('messages.download') }}
                    </a>
                </div>
                {{-- PDF preview --}}
                @if($isPdf && $previewUrl)
                <div class="mt-3">
                    <iframe src="{{ $previewUrl }}" class="w-full rounded border border-emerald-100" style="height:500px;"></iframe>
                </div>
                @elseif($isImage && $previewUrl)
                <div class="mt-3">
                    <img src="{{ $previewUrl }}" alt="{{ $attachName }}" class="max-w-full rounded border border-emerald-100">
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    {{-- Navigation --}}
    @php
        $allLessons = $course->lessons()->orderBy('order')->get();
        $currentIdx = $allLessons->search(fn($l) => $l->id === $lesson->id);
        $prevLesson = $currentIdx > 0 ? $allLessons[$currentIdx - 1] : null;
        $nextLesson = isset($allLessons[$currentIdx + 1]) ? $allLessons[$currentIdx + 1] : null;
    @endphp
    <div class="flex items-center justify-between">
        @if($prevLesson)
        <a href="{{ route('student.courses.lesson', [$course, $prevLesson]) }}" class="flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:border-emerald-400 hover:text-emerald-700 transition text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            {{ __('messages.previous_lesson') }}
        </a>
        @else<span></span>@endif

        <a href="{{ route('student.courses.show', $course) }}" class="text-sm text-emerald-600 hover:underline">{{ __('messages.back_to_course') }}</a>

        @if($nextLesson)
        <a href="{{ route('student.courses.lesson', [$course, $nextLesson]) }}" class="flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition text-sm">
            {{ __('messages.next_lesson') }}
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        @else
        <a href="{{ route('student.courses.show', $course) }}" class="flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition text-sm">
            {{ __('messages.finish_course') }}
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </a>
        @endif
    </div>
</div>
@endsection
