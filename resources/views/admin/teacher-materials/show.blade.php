@extends('layouts.admin')
@section('title', $teacherMaterial->title_localized)
@section('page-title', __('messages.teacher_library'))

@section('content')
<div class="max-w-3xl space-y-5">

    {{-- Back --}}
    <a href="{{ route('admin.teacher-materials.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-emerald-600 transition mb-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        {{ __('messages.back') }}
    </a>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-emerald-600 to-purple-700 px-6 py-5 flex items-start gap-4">
            <div class="w-16 h-16 rounded-xl bg-white/15 flex items-center justify-center text-3xl shrink-0">
                @if($teacherMaterial->cover_image)
                    <img src="{{ $teacherMaterial->cover_url }}" class="w-full h-full rounded-xl object-cover">
                @else
                    {{ $teacherMaterial->type_icon }}
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="text-white font-black text-lg leading-tight">{{ $teacherMaterial->title_localized }}</h2>
                @if($teacherMaterial->source)
                <p class="text-emerald-200 text-sm mt-0.5">{{ $teacherMaterial->source }}</p>
                @endif
                <div class="flex flex-wrap items-center gap-2 mt-2">
                    @php $tc = \App\Models\TeacherMaterial::typeColor($teacherMaterial->material_type); @endphp
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-white/20 text-white uppercase">{{ $teacherMaterial->material_type }}</span>
                    @if($teacherMaterial->status === 'active')
                        <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-400/30 text-white font-semibold">{{ __('messages.active') }}</span>
                    @else
                        <span class="text-xs px-2 py-0.5 rounded-full bg-amber-400/30 text-white font-semibold">{{ __('messages.draft') }}</span>
                    @endif
                </div>
            </div>
            <a href="{{ route('admin.teacher-materials.edit', $teacherMaterial) }}"
               class="shrink-0 bg-white/15 hover:bg-white/25 text-white border border-white/20 px-3 py-1.5 rounded-lg text-sm font-medium transition">
                {{ __('messages.edit') }}
            </a>
        </div>

        {{-- Details grid --}}
        <div class="p-6 grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm border-b border-gray-100">
            @foreach([
                [__('messages.subject'),      $teacherMaterial->subject       ?: '—'],
                [__('messages.grade_level'),  $teacherMaterial->grade_level   ?: '—'],
                [__('messages.language'),     ucfirst($teacherMaterial->language)],
                [__('messages.published_year'),$teacherMaterial->published_year ?: '—'],
                [__('messages.views'),         number_format($teacherMaterial->view_count)],
                [__('messages.downloads'),     number_format($teacherMaterial->download_count)],
            ] as [$label, $val])
            <div>
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-0.5">{{ $label }}</div>
                <div class="text-gray-800 font-medium">{{ $val }}</div>
            </div>
            @endforeach
        </div>

        {{-- Description --}}
        @if($teacherMaterial->description)
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">{{ __('messages.description') }}</h3>
            <p class="text-sm text-gray-700 leading-relaxed">{{ $teacherMaterial->description }}</p>
        </div>
        @endif

        {{-- Tags --}}
        @if($teacherMaterial->tags)
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">{{ __('messages.tags') }}</h3>
            <div class="flex flex-wrap gap-1.5">
                @foreach($teacherMaterial->tags as $tag)
                <span class="text-xs bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-0.5 rounded-full">{{ $tag }}</span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Action buttons --}}
        <div class="p-6 flex flex-wrap gap-3">
            @if($teacherMaterial->external_url)
            <a href="{{ route('admin.teacher-materials.download', $teacherMaterial) }}" target="_blank"
               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                {{ __('messages.open_external') }}
            </a>
            @elseif($teacherMaterial->file_path)
            <a href="{{ route('admin.teacher-materials.download', $teacherMaterial) }}"
               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                {{ __('messages.download') }}
            </a>
            @endif
        </div>
    </div>
</div>
@endsection
