@extends('layouts.lms')
@section('title', __('messages.dashboard'))

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ __('messages.welcome_back') }}, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-500 text-sm mt-1">{{ __('messages.student_dashboard_subtitle') }}</p>
    </div>

    {{-- Stats row --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @foreach([
            ['label' => 'messages.enrolled_courses', 'value' => $enrollments->count(), 'color' => 'emerald', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
            ['label' => 'messages.certificates', 'value' => $certificates->count(), 'color' => 'green', 'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
            ['label' => 'messages.present', 'value' => $attendanceStats['present'] ?? 0, 'color' => 'blue', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
            ['label' => 'messages.absent', 'value' => $attendanceStats['absent'] ?? 0, 'color' => 'red', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ] as $s)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-{{ $s['color'] }}-100 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-{{ $s['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/></svg>
            </div>
            <div>
                <div class="text-xl font-bold text-gray-800">{{ $s['value'] }}</div>
                <div class="text-xs text-gray-500">{{ __($s['label']) }}</div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- My courses --}}
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-gray-800">{{ __('messages.my_courses') }}</h2>
                <a href="{{ route('student.courses.index') }}" class="text-emerald-600 hover:text-emerald-700 text-sm">{{ __('messages.view_all') }}</a>
            </div>
            <div class="space-y-3">
                @forelse($enrollments->take(6) as $enrollment)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg overflow-hidden shrink-0">
                            @if($enrollment->course->thumbnail)<img src="{{ $enrollment->course->thumbnail_url }}" class="w-full h-full object-cover">@else<div class="w-full h-full flex items-center justify-center text-emerald-600 text-xs font-bold">📚</div>@endif
                        </div>
                        <div class="min-w-0">
                            <div class="font-medium text-gray-800 truncate">{{ $enrollment->course->title_localized }}</div>
                            <div class="text-xs text-gray-400">{{ $enrollment->course->teacher->name ?? '-' }}</div>
                            <div class="mt-1.5 h-1.5 bg-gray-100 rounded-full w-32">
                                <div class="h-1.5 bg-emerald-500 rounded-full" style="width:{{ $enrollment->progress }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <div class="text-sm font-semibold text-emerald-700">{{ $enrollment->progress }}%</div>
                        <a href="{{ route('student.courses.show', $enrollment->course) }}" class="text-xs text-emerald-600 hover:underline">{{ __('messages.continue') }}</a>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center text-gray-400">
                    {{ __('messages.not_enrolled_yet') }}
                </div>
                @endforelse
            </div>
        </div>

        {{-- Certificates --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-gray-800">{{ __('messages.my_certificates') }}</h2>
                <a href="{{ route('student.certificates.index') }}" class="text-emerald-600 hover:text-emerald-700 text-sm">{{ __('messages.view_all') }}</a>
            </div>
            <div class="space-y-3">
                @forelse($certificates->take(4) as $cert)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-800 truncate">{{ $cert->course->title_localized ?? '-' }}</div>
                            <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($cert->issued_at)->format('d M Y') }}</div>
                            <a href="{{ route('student.certificates.download', $cert) }}" class="text-xs text-emerald-600 hover:underline mt-1 inline-block">{{ __('messages.download') }}</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center text-gray-400 text-sm">{{ __('messages.no_certificates_yet') }}</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
