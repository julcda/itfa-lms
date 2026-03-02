@extends('layouts.lms')
@section('title', $book->title_localized)

@push('styles')
<style>
.show-hero { background: linear-gradient(135deg, #0d1b2e 0%, #064e3b 100%); position: relative; overflow: hidden; }
.show-hero::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse 70% 80% at 80% 50%, rgba(52,211,153,0.15) 0%, transparent 60%);
    pointer-events: none;
}
@keyframes badge-in { from{opacity:0;transform:scale(.9)} to{opacity:1;transform:scale(1)} }
.badge-in { animation: badge-in .35s ease both; }
.action-btn { transition: transform .15s, box-shadow .15s; }
.action-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
.tag-pill:hover { background: #d1fae5; color: #065f46; }
</style>
@endpush

@section('content')

{{-- Back nav --}}
<div class="bg-white border-b border-gray-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-3">
        <a href="{{ route('student.library.index') }}" class="inline-flex items-center gap-1.5 text-sm text-emerald-600 hover:text-emerald-800 font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            {{ __('messages.back_to_library') }}
        </a>
    </div>
</div>

{{-- Hero banner --}}
<section class="show-hero">
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 py-10 lg:py-14">
        <div class="flex flex-col sm:flex-row gap-8 items-start sm:items-end">
            {{-- Cover --}}
            <div class="shrink-0 badge-in">
                <div class="w-36 sm:w-44 rounded-2xl overflow-hidden shadow-2xl border-2 border-white/10"
                     style="background:linear-gradient(135deg,#1e3a5f,#0d4d3f)">
                    @if($book->cover_image)
                        <img src="{{ $book->cover_url }}" alt="{{ $book->title_localized }}" class="w-full aspect-[2/3] object-cover">
                    @else
                        <div class="w-full aspect-[2/3] flex flex-col items-center justify-center gap-3 p-4">
                            <svg class="w-14 h-14 text-emerald-400/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.4" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13"/></svg>
                            <p class="text-white/70 text-[11px] text-center font-medium leading-tight">{{ $book->title_localized }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Meta --}}
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap gap-2 mb-3">
                    <span class="text-xs font-bold uppercase px-2.5 py-1 rounded-full bg-emerald-400/20 text-emerald-300 border border-emerald-400/30">
                        {{ $book->file_type }}
                    </span>
                    @if($book->category)
                    <span class="text-xs px-2.5 py-1 rounded-full bg-white/10 text-white/70 border border-white/15">
                        {{ $book->category->name_localized }}
                    </span>
                    @endif
                    @if($book->language)
                    <span class="text-xs px-2.5 py-1 rounded-full bg-white/10 text-white/70 border border-white/15 capitalize">
                        {{ $book->language }}
                    </span>
                    @endif
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-white leading-snug mb-1">{{ $book->title }}</h1>
                @if($book->title_ar)
                <p class="text-lg text-white/60 font-medium" dir="rtl">{{ $book->title_ar }}</p>
                @endif
                @if($book->author)
                <p class="text-emerald-300 text-sm mt-2 font-medium">{{ $book->author }}</p>
                @endif

                {{-- Stats --}}
                <div class="flex flex-wrap gap-5 mt-4 text-sm text-white/60">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <strong class="text-white">{{ number_format($book->view_count) }}</strong> {{ __('messages.views') }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <strong class="text-white">{{ number_format($book->download_count) }}</strong> {{ __('messages.downloads') }}
                    </span>
                </div>

                {{-- Action buttons --}}
                <div class="flex flex-wrap gap-3 mt-6">
                    @if($book->external_url)
                    <a href="{{ $book->external_url }}" target="_blank"
                       class="action-btn inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white"
                       style="background:linear-gradient(135deg,#3b82f6,#1d4ed8)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        {{ __('messages.open_external') }}
                    </a>
                    @endif
                    @if($book->file_path || $book->external_url)
                    <a href="{{ route('student.library.download', $book) }}"
                       class="action-btn inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white"
                       style="background:linear-gradient(135deg,#10b981,#059669)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        {{ __('messages.download') }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Details card --}}
<div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main: description + tags --}}
        <div class="lg:col-span-2 space-y-5">
            @if($book->description || $book->description_ar)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-3">{{ __('messages.description') }}</h2>
                @if($book->description)
                <p class="text-gray-700 text-sm leading-relaxed">{{ $book->description }}</p>
                @endif
                @if($book->description_ar)
                <p class="text-gray-600 text-sm leading-relaxed mt-3 font-arabic" dir="rtl">{{ $book->description_ar }}</p>
                @endif
            </div>
            @endif

            @if($book->tags && count($book->tags))
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-3">{{ __('messages.tags') }}</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($book->tags as $tag)
                    <span class="tag-pill bg-gray-100 text-gray-600 text-xs px-3 py-1 rounded-full cursor-default transition">{{ $tag }}</span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar: metadata --}}
        <div class="space-y-5">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h2 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-4">{{ __('messages.details') }}</h2>
                <dl class="space-y-3 text-sm">
                    @if($book->author)
                    <div class="flex justify-between gap-2">
                        <dt class="text-gray-400">{{ __('messages.author') }}</dt>
                        <dd class="text-gray-700 font-medium text-end">{{ $book->author }}</dd>
                    </div>
                    @endif
                    @if($book->isbn)
                    <div class="flex justify-between gap-2">
                        <dt class="text-gray-400">ISBN</dt>
                        <dd class="text-gray-700 font-mono text-end">{{ $book->isbn }}</dd>
                    </div>
                    @endif
                    @if($book->language)
                    <div class="flex justify-between gap-2">
                        <dt class="text-gray-400">{{ __('messages.language') }}</dt>
                        <dd class="text-gray-700 font-medium capitalize text-end">{{ $book->language }}</dd>
                    </div>
                    @endif
                    @if($book->edition)
                    <div class="flex justify-between gap-2">
                        <dt class="text-gray-400">{{ __('messages.edition') }}</dt>
                        <dd class="text-gray-700 font-medium text-end">{{ $book->edition }}</dd>
                    </div>
                    @endif
                    @if($book->grade_level)
                    <div class="flex justify-between gap-2">
                        <dt class="text-gray-400">{{ __('messages.grade_level') }}</dt>
                        <dd class="text-gray-700 font-medium text-end">{{ $book->grade_level }}</dd>
                    </div>
                    @endif
                    @if($book->category)
                    <div class="flex justify-between gap-2">
                        <dt class="text-gray-400">{{ __('messages.category') }}</dt>
                        <dd class="text-gray-700 font-medium text-end">{{ $book->category->name_localized }}</dd>
                    </div>
                    @endif
                    <div class="flex justify-between gap-2 pt-2 border-t border-gray-50">
                        <dt class="text-gray-400">{{ __('messages.format') }}</dt>
                        <dd class="font-bold text-emerald-600 uppercase text-end">{{ $book->file_type }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Stats card --}}
            <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl border border-emerald-100 p-5">
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div>
                        <p class="text-2xl font-black text-emerald-700">{{ number_format($book->view_count) }}</p>
                        <p class="text-xs text-emerald-500 mt-0.5">{{ __('messages.views') }}</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-teal-700">{{ number_format($book->download_count) }}</p>
                        <p class="text-xs text-teal-500 mt-0.5">{{ __('messages.downloads') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

