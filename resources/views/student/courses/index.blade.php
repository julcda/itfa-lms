@extends('layouts.lms')
@section('title', __('messages.my_courses'))

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ __('messages.my_courses') }}</h1>
        <p class="text-gray-500 text-sm mt-1">{{ __('messages.enrolled_courses_subtitle') }}</p>
    </div>

    @if($enrollments->isEmpty())
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
        <div class="text-5xl mb-4">📚</div>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">{{ __('messages.not_enrolled_yet') }}</h3>
        <p class="text-gray-400 mb-4">{{ __('messages.browse_courses_prompt') }}</p>
        <a href="{{ route('home') }}" class="bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700 transition">{{ __('messages.browse_courses') }}</a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($enrollments as $enrollment)
        @php $course = $enrollment->course; @endphp
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
            <div class="h-40 bg-emerald-50 overflow-hidden relative">
                @if($course->thumbnail)
                <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title_localized }}" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center text-4xl">📘</div>
                @endif
                <div class="absolute top-2 end-2">
                    @php $pct = $enrollment->progress ?? 0; @endphp
                    @if($pct >= 100)
                    <span class="bg-emerald-600 text-white text-xs px-2 py-0.5 rounded-full font-medium">{{ __('messages.completed') }}</span>
                    @elseif($pct > 0)
                    <span class="bg-blue-600 text-white text-xs px-2 py-0.5 rounded-full font-medium">{{ __('messages.in_progress') }}</span>
                    @else
                    <span class="bg-gray-400 text-white text-xs px-2 py-0.5 rounded-full font-medium">{{ __('messages.not_started') }}</span>
                    @endif
                </div>
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-gray-800 mb-1 line-clamp-2">{{ $course->title_localized }}</h3>
                <p class="text-xs text-gray-400 mb-3">{{ $course->teacher->name ?? '-' }}</p>

                <div class="mb-3">
                    <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                        <span>{{ __('messages.progress') }}</span>
                        <span>{{ $pct }}%</span>
                    </div>
                    <div class="h-1.5 bg-gray-100 rounded-full">
                        <div class="h-1.5 bg-emerald-500 rounded-full transition-all" style="width:{{ $pct }}%"></div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="text-xs text-gray-400">
                        {{ __('messages.enrolled') }}: {{ \Carbon\Carbon::parse($enrollment->created_at)->format('d M Y') }}
                    </div>
                    <a href="{{ route('student.courses.show', $course) }}" class="bg-emerald-600 text-white text-xs px-3 py-1.5 rounded-lg hover:bg-emerald-700 transition">
                        {{ $pct > 0 ? __('messages.continue') : __('messages.start') }}
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $enrollments->links() }}
    </div>
    @endif
</div>
@endsection
