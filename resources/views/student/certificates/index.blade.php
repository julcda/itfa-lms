@extends('layouts.lms')
@section('title', __('messages.my_certificates'))

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ __('messages.my_certificates') }}</h1>
        <p class="text-gray-500 text-sm mt-1">{{ __('messages.certificates_subtitle') }}</p>
    </div>

    @if($certificates->isEmpty())
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
        <div class="text-5xl mb-4">🏆</div>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">{{ __('messages.no_certificates_yet') }}</h3>
        <p class="text-gray-400 text-sm mb-4">{{ __('messages.complete_courses_for_certificates') }}</p>
        <a href="{{ route('student.courses.index') }}" class="bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700 transition text-sm inline-block">{{ __('messages.my_courses') }}</a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach($certificates as $cert)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            {{-- Certificate card preview --}}
            <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 p-6 text-center relative">
                <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, white 0, white 1px, transparent 0, transparent 50%); background-size: 10px 10px;"></div>
                <div class="relative">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-white/20 rounded-full mb-2">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    </div>
                    <div class="text-white/80 text-xs uppercase tracking-widest">{{ __('messages.certificate_of_completion') }}</div>
                    <div class="text-white font-bold text-base mt-1 line-clamp-2">{{ $cert->course->title_localized ?? '-' }}</div>
                </div>
            </div>

            <div class="p-4">
                <div class="grid grid-cols-2 gap-2 text-sm mb-4">
                    <div>
                        <div class="text-xs text-gray-400">{{ __('messages.certificate_number') }}</div>
                        <div class="font-mono text-gray-700 font-medium text-xs">{{ $cert->certificate_number }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400">{{ __('messages.issued_at') }}</div>
                        <div class="text-gray-700 font-medium text-xs">{{ \Carbon\Carbon::parse($cert->issued_at)->format('d M Y') }}</div>
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('student.certificates.download', $cert) }}" class="flex-1 flex items-center justify-center gap-2 bg-emerald-600 text-white py-2 rounded-lg hover:bg-emerald-700 transition text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        {{ __('messages.download_pdf') }}
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
