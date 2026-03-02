@extends('layouts.admin')

@section('title', __('messages.dashboard'))
@section('page-title', __('messages.dashboard'))

@section('content')
{{-- Stats grid --}}
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
    @foreach([
        ['label' => 'messages.students',    'value' => $stats['students'],    'c1' => '#3b82f6', 'c2' => '#1d4ed8', 'shadow' => 'rgba(59,130,246,0.35)',  'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
        ['label' => 'messages.teachers',    'value' => $stats['teachers'],    'c1' => '#8b5cf6', 'c2' => '#6d28d9', 'shadow' => 'rgba(139,92,246,0.35)', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
        ['label' => 'messages.courses',     'value' => $stats['courses'],     'c1' => '#10b981', 'c2' => '#047857', 'shadow' => 'rgba(16,185,129,0.35)',  'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
        ['label' => 'messages.books',       'value' => $stats['books'],       'c1' => '#f59e0b', 'c2' => '#b45309', 'shadow' => 'rgba(245,158,11,0.35)',  'icon' => 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z'],
        ['label' => 'messages.enrollments', 'value' => $stats['enrollments'], 'c1' => '#06b6d4', 'c2' => '#0e7490', 'shadow' => 'rgba(6,182,212,0.35)',   'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
        ['label' => 'messages.certificates','value' => $stats['certificates'],'c1' => '#f43f5e', 'c2' => '#be123c', 'shadow' => 'rgba(244,63,94,0.35)',   'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
    ] as $stat)
    <div class="stat-card rounded-2xl p-5 text-white relative overflow-hidden"
         style="background: linear-gradient(135deg, {{ $stat['c1'] }} 0%, {{ $stat['c2'] }} 100%); box-shadow: 0 8px 24px {{ $stat['shadow'] }};">
        {{-- Decorative circles --}}
        <div class="absolute -bottom-4 -end-4 w-24 h-24 rounded-full pointer-events-none" style="background:rgba(255,255,255,0.08)"></div>
        <div class="absolute -top-3 -start-3 w-16 h-16 rounded-full pointer-events-none" style="background:rgba(255,255,255,0.05)"></div>
        {{-- Icon --}}
        <div class="w-11 h-11 rounded-xl mb-3 flex items-center justify-center relative" style="background:rgba(255,255,255,0.2)">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/></svg>
        </div>
        <div class="text-3xl font-black tracking-tight leading-none">{{ number_format($stat['value']) }}</div>
        <div class="text-white/75 text-xs font-semibold mt-1.5 uppercase tracking-wider">{{ __($stat['label']) }}</div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Recent Enrollments --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden" style="border: 1px solid #d1fae5; box-shadow: 0 4px 20px rgba(16,185,129,0.08);">
        <div class="flex items-center justify-between p-5" style="background: linear-gradient(90deg, #ecfdf5, #f0fdf4); border-bottom: 1px solid #d1fae5;">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#10b981">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                </div>
                <h3 class="font-bold text-gray-800 text-[14px]">{{ __('messages.recent_enrollments') }}</h3>
            </div>
            <a href="{{ route('admin.courses.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-lg transition">{{ __('messages.view_all') }}</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentEnrollments as $enrollment)
            <div class="px-5 py-3 flex items-center justify-between hover:bg-emerald-50/50 transition">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold" style="background: linear-gradient(135deg,#10b981,#047857)">{{ strtoupper(substr($enrollment->user->name ?? 'U', 0, 1)) }}</div>
                    <div>
                        <div class="text-sm font-medium text-gray-800">{{ $enrollment->user->name ?? '-' }}</div>
                        <div class="text-xs text-gray-400 truncate max-w-xs">{{ $enrollment->course->title_localized ?? '-' }}</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-gray-400">{{ $enrollment->created_at->diffForHumans() }}</div>
                    <span class="inline-block px-2 py-0.5 rounded-full text-xs {{ $enrollment->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $enrollment->status }}</span>
                </div>
            </div>
            @empty
            <div class="px-5 py-6 text-center text-gray-400 text-sm">{{ __('messages.no_data_yet') }}</div>
            @endforelse
        </div>
    </div>

    {{-- Recent Users --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden" style="border: 1px solid #e0e7ff; box-shadow: 0 4px 20px rgba(99,102,241,0.08);">
        <div class="flex items-center justify-between p-5" style="background: linear-gradient(90deg, #eef2ff, #f5f3ff); border-bottom: 1px solid #e0e7ff;">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#6366f1">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="font-bold text-gray-800 text-[14px]">{{ __('messages.recent_users') }}</h3>
            </div>
            @hasrole('admin')<a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition">{{ __('messages.view_all') }}</a>@endhasrole
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentUsers as $user)
            <div class="px-5 py-3 flex items-center justify-between hover:bg-indigo-50/40 transition">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold" style="background: linear-gradient(135deg,#6366f1,#4f46e5)">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <div>
                        <div class="text-sm font-medium text-gray-800">{{ $user->name }}</div>
                        <div class="text-xs text-gray-400">{{ $user->email }}</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</div>
                    @foreach($user->getRoleNames() as $role)
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs bg-emerald-100 text-emerald-700">{{ $role }}</span>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="px-5 py-6 text-center text-gray-400 text-sm">{{ __('messages.no_data_yet') }}</div>
            @endforelse
        </div>
    </div>
</div>

{{-- Quick actions --}}
<div class="mt-6 rounded-2xl overflow-hidden" style="background: linear-gradient(135deg, #1e3a5f 0%, #0f2341 100%); box-shadow: 0 8px 30px rgba(15,35,65,0.3);">
    <div class="px-5 pt-5 pb-2">
        <div class="flex items-center gap-2 mb-1">
            <svg class="w-4 h-4" style="color:#34d399" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <h3 class="font-bold text-white text-[14px] tracking-wide">{{ __('messages.quick_actions') }}</h3>
        </div>
        <p class="text-white/40 text-xs">{{ now()->format('l, d F Y') }}</p>
    </div>
    <div class="flex flex-wrap gap-3 px-5 py-4">
        <a href="{{ route('admin.courses.create') }}"
           class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white transition hover:scale-105 active:scale-95"
           style="background: linear-gradient(135deg,#10b981,#047857); box-shadow: 0 4px 12px rgba(16,185,129,0.4);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('messages.add_course') }}
        </a>
        <a href="{{ route('admin.books.create') }}"
           class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white transition hover:scale-105 active:scale-95"
           style="background: linear-gradient(135deg,#f59e0b,#b45309); box-shadow: 0 4px 12px rgba(245,158,11,0.4);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('messages.add_book') }}
        </a>
        @hasrole('admin')
        <a href="{{ route('admin.users.create') }}"
           class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white transition hover:scale-105 active:scale-95"
           style="background: linear-gradient(135deg,#3b82f6,#1d4ed8); box-shadow: 0 4px 12px rgba(59,130,246,0.4);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('messages.add_user') }}
        </a>
        @endhasrole
        <a href="{{ route('admin.quizzes.create') }}"
           class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white transition hover:scale-105 active:scale-95"
           style="background: linear-gradient(135deg,#8b5cf6,#6d28d9); box-shadow: 0 4px 12px rgba(139,92,246,0.4);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('messages.add_quiz') }}
        </a>
    </div>
</div>
@endsection
