@extends('layouts.admin')
@section('title', $lesson->title_localized)
@section('page-title', __('messages.lesson_details'))

@section('content')

{{-- Breadcrumb --}}
<nav class="text-sm text-gray-500 mb-5 flex items-center gap-2">
    <a href="{{ route('admin.courses.index') }}" class="hover:text-emerald-600">{{ __('messages.courses') }}</a>
    <span>/</span>
    <a href="{{ route('admin.courses.show', $lesson->course) }}" class="hover:text-emerald-600">{{ $lesson->course->title_localized }}</a>
    <span>/</span>
    <span class="text-gray-700 font-medium">{{ $lesson->title_localized }}</span>
</nav>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Main Column --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Header Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <div class="flex items-center gap-2 flex-wrap mb-1">
                        @php
                            $typeColors = ['video'=>'bg-blue-100 text-blue-700','text'=>'bg-amber-100 text-amber-700','quiz'=>'bg-purple-100 text-purple-700','assignment'=>'bg-orange-100 text-orange-700','pdf'=>'bg-red-100 text-red-700'];
                            $typeColor = $typeColors[$lesson->type] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $typeColor }}">{{ ucfirst($lesson->type ?? 'lesson') }}</span>

                        @if($lesson->is_free)
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">{{ __('messages.free') }}</span>
                        @endif

                        @php $statusColors = ['active'=>'bg-emerald-100 text-emerald-700','draft'=>'bg-gray-100 text-gray-600','inactive'=>'bg-red-100 text-red-600']; @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $statusColors[$lesson->status] ?? 'bg-gray-100 text-gray-600' }}">{{ ucfirst($lesson->status ?? 'draft') }}</span>

                        @if($lesson->order)
                            <span class="text-xs text-gray-400">#{{ $lesson->order }}</span>
                        @endif
                    </div>
                    <h1 class="text-xl font-bold text-gray-800 leading-snug">{{ $lesson->title }}</h1>
                    @if($lesson->title_ar)
                        <p class="text-gray-500 text-sm dir-rtl" dir="rtl">{{ $lesson->title_ar }}</p>
                    @endif
                    @if($lesson->duration)
                        <p class="text-gray-400 text-xs mt-1">⏱ {{ $lesson->duration }} {{ __('messages.minutes') }}</p>
                    @endif
                </div>
                <div class="flex gap-2 flex-wrap">
                    <a href="{{ route('admin.courses.lessons.edit', [$lesson->course_id, $lesson]) }}"
                        class="bg-amber-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-amber-600 transition">✏️ {{ __('messages.edit') }}</a>
                    <form method="POST" action="{{ route('admin.courses.lessons.destroy', [$lesson->course_id, $lesson]) }}"
                        onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-600 transition">🗑 {{ __('messages.delete') }}</button>
                    </form>
                    <a href="{{ route('admin.courses.show', $lesson->course) }}"
                        class="border border-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition">← {{ __('messages.back') }}</a>
                </div>
            </div>
        </div>

        {{-- Video Player --}}
        @if($lesson->video_url)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-3 flex items-center gap-2"><span class="text-blue-500">▶</span> {{ __('messages.video') }}</h3>
            @php
                $videoUrl = $lesson->video_url;
                $embedUrl = null;
                if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/))([A-Za-z0-9_\-]{11})/', $videoUrl, $m)) {
                    $embedUrl = 'https://www.youtube.com/embed/' . $m[1];
                } elseif (preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $m)) {
                    $embedUrl = 'https://player.vimeo.com/video/' . $m[1];
                }
            @endphp
            @if($embedUrl)
                <div class="rounded-lg overflow-hidden bg-black" style="aspect-ratio:16/9">
                    <iframe src="{{ $embedUrl }}" class="w-full h-full" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                </div>
            @else
                <a href="{{ $videoUrl }}" target="_blank" class="inline-flex items-center gap-2 bg-blue-50 text-blue-600 px-4 py-2 rounded-lg text-sm hover:bg-blue-100 transition">
                    ▶ {{ __('messages.open_video') }} <span class="text-xs text-blue-400">↗</span>
                </a>
            @endif
        </div>
        @endif

        {{-- Content (EN + AR) --}}
        @if($lesson->content || $lesson->content_ar)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-4">{{ __('messages.content') }}</h3>
            <div class="grid grid-cols-1 {{ $lesson->content && $lesson->content_ar ? 'md:grid-cols-2' : '' }} gap-5">
                @if($lesson->content)
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase mb-2">English</p>
                    <div class="prose prose-sm max-w-none text-gray-700 bg-gray-50 rounded-lg p-4">
                        {!! nl2br(e($lesson->content)) !!}
                    </div>
                </div>
                @endif
                @if($lesson->content_ar)
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase mb-2">عربي</p>
                    <div class="prose prose-sm max-w-none text-gray-700 bg-amber-50 rounded-lg p-4 text-right" dir="rtl">
                        {!! nl2br(e($lesson->content_ar)) !!}
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Attachment --}}
        @if($lesson->attachment)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-3">{{ __('messages.attachment') }}</h3>
            <a href="{{ asset('storage/' . $lesson->attachment) }}" download
                class="inline-flex items-center gap-2 bg-indigo-50 text-indigo-600 px-4 py-2 rounded-lg text-sm hover:bg-indigo-100 transition">
                📎 {{ basename($lesson->attachment) }}
            </a>
        </div>
        @endif

        {{-- Quizzes --}}
        @if($lesson->quizzes && $lesson->quizzes->count())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-3">{{ __('messages.quizzes') }}</h3>
            <div class="space-y-2">
                @foreach($lesson->quizzes as $quiz)
                <div class="flex items-center justify-between bg-purple-50 rounded-lg px-4 py-3">
                    <div>
                        <span class="font-medium text-purple-700 text-sm">{{ $quiz->title }}</span>
                        @if($quiz->questions_count ?? $quiz->questions?->count())
                            <span class="text-xs text-gray-400 ml-2">{{ $quiz->questions_count ?? $quiz->questions->count() }} {{ __('messages.questions') }}</span>
                        @endif
                    </div>
                    <a href="{{ route('admin.quizzes.show', $quiz) }}" class="text-xs text-purple-600 hover:text-purple-800 font-medium">{{ __('messages.view') }}</a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- Sidebar --}}
    <div class="space-y-5">

        {{-- Lesson Meta --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-4">{{ __('messages.lesson_info') }}</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">{{ __('messages.course') }}</dt>
                    <dd class="text-right"><a href="{{ route('admin.courses.show', $lesson->course) }}" class="text-emerald-600 hover:underline font-medium text-xs">{{ $lesson->course->title_localized }}</a></dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">{{ __('messages.type') }}</dt>
                    <dd class="font-medium text-gray-700">{{ ucfirst($lesson->type ?? '-') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">{{ __('messages.status') }}</dt>
                    <dd>
                        @php $sc = ['active'=>'text-emerald-600','draft'=>'text-gray-500','inactive'=>'text-red-500']; @endphp
                        <span class="font-medium {{ $sc[$lesson->status] ?? 'text-gray-500' }}">{{ ucfirst($lesson->status ?? '-') }}</span>
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">{{ __('messages.order') }}</dt>
                    <dd class="font-medium text-gray-700">{{ $lesson->order ?? '-' }}</dd>
                </div>
                @if($lesson->duration)
                <div class="flex justify-between">
                    <dt class="text-gray-500">{{ __('messages.duration') }}</dt>
                    <dd class="font-medium text-gray-700">{{ $lesson->duration }} {{ __('messages.minutes') }}</dd>
                </div>
                @endif
                <div class="flex justify-between">
                    <dt class="text-gray-500">{{ __('messages.free_preview') }}</dt>
                    <dd class="{{ $lesson->is_free ? 'text-green-600' : 'text-gray-400' }} font-medium">{{ $lesson->is_free ? __('messages.yes') : __('messages.no') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">{{ __('messages.created_at') }}</dt>
                    <dd class="text-gray-600 text-xs">{{ $lesson->created_at->format('d M Y') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">{{ __('messages.updated_at') }}</dt>
                    <dd class="text-gray-600 text-xs">{{ $lesson->updated_at->format('d M Y') }}</dd>
                </div>
            </dl>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-3">{{ __('messages.quick_actions') }}</h3>
            <div class="space-y-2">
                <a href="{{ route('admin.courses.lessons.edit', [$lesson->course_id, $lesson]) }}"
                    class="flex items-center gap-2 px-3 py-2 text-sm text-amber-700 bg-amber-50 rounded-lg hover:bg-amber-100 transition">
                    ✏️ {{ __('messages.edit_lesson') }}
                </a>
                <a href="{{ route('admin.courses.lessons.create', $lesson->course_id) }}"
                    class="flex items-center gap-2 px-3 py-2 text-sm text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                    + {{ __('messages.add_lesson') }}
                </a>
                <a href="{{ route('admin.enrollments.create', ['course_id' => $lesson->course_id]) }}"
                    class="flex items-center gap-2 px-3 py-2 text-sm text-violet-700 bg-violet-50 rounded-lg hover:bg-violet-100 transition">
                    👥 {{ __('messages.enroll_students') }}
                </a>
                <a href="{{ route('admin.courses.show', $lesson->course) }}"
                    class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    ← {{ __('messages.back_to_course') }}
                </a>
            </div>
        </div>

        {{-- Other Lessons in this Course --}}
        @if($lesson->course->lessons && $lesson->course->lessons->count() > 1)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-3 text-sm">{{ __('messages.other_lessons') }}</h3>
            <div class="space-y-1.5 max-h-64 overflow-y-auto">
                @foreach($lesson->course->lessons->sortBy('order') as $other)
                    @if($other->id !== $lesson->id)
                    <a href="{{ route('admin.courses.lessons.show', [$lesson->course_id, $other]) }}"
                        class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-gray-50 transition">
                        <span class="text-xs text-gray-400 w-4">{{ $other->order }}</span>
                        <span class="text-xs text-gray-700 truncate flex-1">{{ $other->title }}</span>
                        @php $tc = ['video'=>'text-blue-400','text'=>'text-amber-400','quiz'=>'text-purple-400']; @endphp
                        <span class="{{ $tc[$other->type] ?? 'text-gray-300' }} text-xs">●</span>
                    </a>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
