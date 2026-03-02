<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('messages.dashboard')) — {{ setting('school_name', 'School') }} Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        [dir="rtl"] { font-family: 'Noto Kufi Arabic', sans-serif; }
        [dir="ltr"] { font-family: 'Inter', sans-serif; }

        /* ── Sidebar collapse ── */
        #sidebar { transition: width 0.28s cubic-bezier(.4,0,.2,1); }
        #sidebar.collapsed { width: 72px; }
        #sidebar.collapsed .nav-label,
        #sidebar.collapsed .section-label,
        #sidebar.collapsed .brand-text,
        #sidebar.collapsed .user-info { display: none; }
        #sidebar.collapsed .nav-item { justify-content: center; padding-inline: 0; }
        #sidebar.collapsed .nav-icon-wrap { box-shadow: 0 0 0 1px rgba(255,255,255,0.08); }

        /* ── Nav item ── */
        .nav-item {
            position: relative; display: flex; align-items: center; gap: 10px;
            padding: 7px 10px; border-radius: 10px; font-size: 0.8125rem;
            font-weight: 500; color: rgba(148,163,184,0.85);
            transition: background 0.15s, color 0.15s, transform 0.14s;
            text-decoration: none; cursor: pointer; width: 100%;
        }
        .nav-item:hover {
            background: rgba(255,255,255,0.08);
            color: #f8fafc;
            transform: translateX(3px);
        }
        [dir="rtl"] .nav-item:hover { transform: translateX(-3px); }
        .nav-item.active {
            background: linear-gradient(135deg, rgba(52,211,153,0.22) 0%, rgba(16,185,129,0.1) 100%);
            color: #fff; font-weight: 600;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.06);
        }
        .nav-item.active::before {
            content: ''; position: absolute; inset-inline-start: 0; top: 5px; bottom: 5px;
            width: 3px; border-radius: 0 4px 4px 0;
            background: linear-gradient(180deg, #6ee7b7 0%, #10b981 100%);
        }
        [dir="rtl"] .nav-item.active::before { border-radius: 4px 0 0 4px; }

        /* ── Nav icon wrap ── */
        .nav-icon-wrap {
            width: 34px; height: 34px; border-radius: 9px; display: flex;
            align-items: center; justify-content: center; flex-shrink: 0;
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .nav-item:hover .nav-icon-wrap { transform: scale(1.08); }
        .nav-item.active .nav-icon-wrap { box-shadow: 0 4px 12px rgba(0,0,0,0.25); }

        /* ── Section labels ── */
        .section-label { letter-spacing: 0.1em; }

        /* ── Scrollbar ── */
        #sidebar-nav::-webkit-scrollbar { width: 3px; }
        #sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius:4px; }

        /* ── Tooltip on collapsed ── */
        .nav-item-wrap { position: relative; }
        #sidebar.collapsed .nav-item-wrap:hover .nav-tooltip { opacity:1; pointer-events:auto; }
        .nav-tooltip {
            position: absolute; inset-inline-start: calc(100% + 12px); top: 50%; transform: translateY(-50%);
            background: #1e3a5f; color: #e2f0ff; font-size: 0.7rem; font-weight: 600;
            padding: 5px 11px; border-radius: 8px; white-space: nowrap;
            pointer-events: none; opacity: 0; transition: opacity 0.15s;
            box-shadow: 0 6px 20px rgba(0,0,0,0.4); z-index: 999;
        }
        .nav-tooltip::before {
            content: ''; position: absolute; inset-inline-end: 100%; top: 50%; transform: translateY(-50%);
            border: 5px solid transparent; border-inline-end-color: #1e3a5f;
        }

        /* ── Page background ── */
        body { background: linear-gradient(135deg, #eef2ff 0%, #f0fdf4 50%, #f0f9ff 100%); }

        /* ── Stat card shimmer ── */
        @keyframes card-in { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
        .stat-card { animation: card-in 0.4s ease both; }
    </style>
    @stack('styles')
</head>
<body class="flex h-screen overflow-hidden bg-slate-100 text-gray-900 antialiased">

    <!-- ══════════════ SIDEBAR ══════════════ -->
    <aside id="sidebar"
           class="w-64 flex flex-col h-full shrink-0 overflow-y-auto
                  border-e border-white/[0.06] shadow-2xl transition-all duration-300"
           style="background: linear-gradient(175deg, #0d1b2e 0%, #0f2341 45%, #091929 100%);">

        {{-- Brand --}}
        <div class="flex items-center gap-3 px-4 py-[14px] shrink-0 relative" style="border-bottom: 1px solid rgba(255,255,255,0.07); background: linear-gradient(180deg, rgba(16,185,129,0.12) 0%, transparent 100%)">
            @php $schoolLogo = setting('school_logo'); @endphp
            @if($schoolLogo)
                <img src="{{ Storage::disk('public')->url($schoolLogo) }}" alt="logo" class="w-9 h-9 rounded-xl object-contain shrink-0" style="background:#fff;padding:2px">
            @else
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-black text-sm shrink-0" style="background: linear-gradient(135deg, #34d399 0%, #059669 60%, #047857 100%); box-shadow: 0 4px 14px rgba(16,185,129,0.45);">
                    {{ strtoupper(substr(setting('school_short_name','S'), 0, 2)) }}
                </div>
            @endif
            <div class="brand-text overflow-hidden flex-1 min-w-0">
                <div class="text-white font-bold text-[13px] truncate leading-tight">{{ school_name() }}</div>
                <div class="text-emerald-400 text-[11px] font-medium">Admin Panel</div>
            </div>
            <button onclick="toggleSidebar()"
                    class="hidden lg:flex w-7 h-7 items-center justify-center rounded-lg text-slate-500 hover:text-slate-200 hover:bg-white/10 transition shrink-0"
                    title="Toggle sidebar">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7M19 19l-7-7 7-7"/></svg>
            </button>
        </div>

        {{-- User pill --}}
        <div class="mx-3 mt-3 mb-1 p-2.5 rounded-xl bg-white/[0.05] border border-white/[0.07] flex items-center gap-2.5 shrink-0">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-xs shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="user-info overflow-hidden flex-1 min-w-0">
                <div class="text-white text-[12px] font-semibold truncate leading-tight">{{ auth()->user()->name }}</div>
                <span class="inline-block mt-0.5 text-[10px] font-bold uppercase tracking-wide bg-emerald-500/20 text-emerald-400 px-1.5 py-px rounded-md">
                    {{ auth()->user()->getRoleNames()->first() ?? 'user' }}
                </span>
            </div>
        </div>

        {{-- Nav --}}
        <nav id="sidebar-nav" class="flex-1 overflow-y-auto px-3 py-2 space-y-px">

            {{-- OVERVIEW --}}
            <p class="section-label text-[10px] font-bold uppercase px-2 pt-2 pb-1" style="color:rgba(52,211,153,0.7)">{{ __('messages.overview') }}</p>
            <div class="nav-item-wrap">
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon-wrap" style="background:rgba(52,211,153,0.15);color:#34d399"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 12a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1v-7z"/></svg></span>
                    <span class="nav-label">{{ __('messages.dashboard') }}</span>
                </a>
                <span class="nav-tooltip">{{ __('messages.dashboard') }}</span>
            </div>

            {{-- ADMINISTRATION --}}
            @hasrole('admin')
            <p class="section-label text-[10px] font-bold uppercase px-2 pt-4 pb-1" style="color:rgba(96,165,250,0.75)">{{ __('messages.administration') }}</p>
            <div class="nav-item-wrap">
                <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <span class="nav-icon-wrap" style="background:rgba(96,165,250,0.15);color:#60a5fa"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></span>
                    <span class="nav-label">{{ __('messages.users') }}</span>
                </a>
                <span class="nav-tooltip">{{ __('messages.users') }}</span>
            </div>
            <div class="nav-item-wrap">
                <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <span class="nav-icon-wrap" style="background:rgba(129,140,248,0.15);color:#818cf8"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg></span>
                    <span class="nav-label">{{ __('messages.categories') }}</span>
                </a>
                <span class="nav-tooltip">{{ __('messages.categories') }}</span>
            </div>
            <div class="nav-item-wrap">
                <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <span class="nav-icon-wrap" style="background:rgba(249,115,22,0.15);color:#f97316"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg></span>
                    <span class="nav-label">{{ __('messages.settings') }}</span>
                </a>
                <span class="nav-tooltip">{{ __('messages.settings') }}</span>
            </div>
            @endhasrole

            {{-- ACADEMICS --}}
            <p class="section-label text-[10px] font-bold uppercase px-2 pt-4 pb-1" style="color:rgba(45,212,191,0.75)">{{ __('messages.academics') }}</p>
            <div class="nav-item-wrap">
                <a href="{{ route('admin.courses.index') }}" class="nav-item {{ request()->routeIs('admin.courses.*') || request()->routeIs('admin.lessons.*') ? 'active' : '' }}">
                    <span class="nav-icon-wrap" style="background:rgba(45,212,191,0.15);color:#2dd4bf"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></span>
                    <span class="nav-label">{{ __('messages.courses') }}</span>
                </a>
                <span class="nav-tooltip">{{ __('messages.courses') }}</span>
            </div>
            <div class="nav-item-wrap">
                <a href="{{ route('admin.books.index') }}" class="nav-item {{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
                    <span class="nav-icon-wrap" style="background:rgba(251,191,36,0.15);color:#fbbf24"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg></span>
                    <span class="nav-label">{{ __('messages.e_library') }}</span>
                </a>
                <span class="nav-tooltip">{{ __('messages.e_library') }}</span>
            </div>
            <div class="nav-item-wrap">
                <a href="{{ route('admin.teacher-materials.index') }}" class="nav-item {{ request()->routeIs('admin.teacher-materials.*') ? 'active' : '' }}">
                    <span class="nav-icon-wrap" style="background:rgba(167,139,250,0.15);color:#a78bfa"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg></span>
                    <span class="nav-label">{{ __('messages.teacher_library') }}</span>
                </a>
                <span class="nav-tooltip">{{ __('messages.teacher_library') }}</span>
            </div>
            <div class="nav-item-wrap">
                <a href="{{ route('admin.quizzes.index') }}" class="nav-item {{ request()->routeIs('admin.quizzes.*') || request()->routeIs('admin.quiz-questions.*') ? 'active' : '' }}">
                    <span class="nav-icon-wrap" style="background:rgba(167,139,250,0.15);color:#a78bfa"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                    <span class="nav-label">{{ __('messages.quizzes') }}</span>
                </a>
                <span class="nav-tooltip">{{ __('messages.quizzes') }}</span>
            </div>
            <div class="nav-item-wrap">
                <a href="{{ route('admin.attendance.index') }}" class="nav-item {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                    <span class="nav-icon-wrap" style="background:rgba(34,211,238,0.15);color:#22d3ee"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg></span>
                    <span class="nav-label">{{ __('messages.attendance') }}</span>
                </a>
                <span class="nav-tooltip">{{ __('messages.attendance') }}</span>
            </div>
            <div class="nav-item-wrap">
                <a href="{{ route('admin.certificates.index') }}" class="nav-item {{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}">
                    <span class="nav-icon-wrap" style="background:rgba(250,204,21,0.15);color:#facc15"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg></span>
                    <span class="nav-label">{{ __('messages.certificates') }}</span>
                </a>
                <span class="nav-tooltip">{{ __('messages.certificates') }}</span>
            </div>
        </nav>

        {{-- Footer --}}
        <div class="px-3 py-3 border-t border-white/[0.07] space-y-px shrink-0">
            <div class="nav-item-wrap">
                <a href="{{ route('home') }}" target="_blank" class="nav-item">
                    <span class="nav-icon-wrap"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg></span>
                    <span class="nav-label">{{ __('messages.view_site') }}</span>
                </a>
                <span class="nav-tooltip">{{ __('messages.view_site') }}</span>
            </div>
            <div class="nav-item-wrap">
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="nav-item" style="color:rgba(252,165,165,0.8)">
                        <span class="nav-icon-wrap" style="color:rgba(252,165,165,0.8)"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg></span>
                        <span class="nav-label">{{ __('messages.logout') }}</span>
                    </button>
                </form>
                <span class="nav-tooltip">{{ __('messages.logout') }}</span>
            </div>
        </div>
    </aside>
    <!-- ══════════════ MAIN AREA ══════════════ -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Top bar -->
        <header class="border-b px-4 lg:px-6 py-3 flex items-center justify-between gap-4 shrink-0" style="background: linear-gradient(90deg, #ffffff 0%, #f0fdf4 100%); border-color: #e2f5eb; box-shadow: 0 1px 8px rgba(16,185,129,0.07);">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="text-gray-400 hover:text-gray-700 p-1.5 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8M4 18h16"/></svg>
                </button>
                <div class="h-5 w-px bg-gray-200 hidden lg:block"></div>
                <div>
                    <h1 class="text-[15px] font-semibold text-gray-800 leading-tight">@yield('page-title', __('messages.dashboard'))</h1>
                    @hasSection('breadcrumbs')
                        <nav class="text-[11px] text-gray-400 mt-0.5">@yield('breadcrumbs')</nav>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex items-center bg-gray-100 rounded-lg p-1 gap-0.5">
                    <a href="{{ route('locale.switch', 'ar') }}" class="px-2.5 py-1 rounded-md text-xs font-semibold transition {{ app()->getLocale()==='ar' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">عربي</a>
                    <a href="{{ route('locale.switch', 'en') }}" class="px-2.5 py-1 rounded-md text-xs font-semibold transition {{ app()->getLocale()==='en' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">EN</a>
                </div>
                <div class="hidden sm:block text-xs text-gray-400 bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5">{{ now()->format('d M Y') }}</div>
            </div>
        </header>

        <!-- Flash messages -->
        <div class="px-4 lg:px-6 space-y-2 mt-3 empty:mt-0">
            @if(session('success'))
            <div class="flex items-center justify-between bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 text-sm shadow-sm">
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded-full bg-emerald-500 flex items-center justify-center shrink-0"><svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></div>
                    {{ session('success') }}
                </div>
                <button onclick="this.closest('div').remove()" class="text-emerald-500 hover:text-emerald-700 ms-4 text-lg leading-none">&times;</button>
            </div>
            @endif
            @if(session('error'))
            <div class="flex items-center justify-between bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm shadow-sm">
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded-full bg-red-500 flex items-center justify-center shrink-0"><svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg></div>
                    {{ session('error') }}
                </div>
                <button onclick="this.closest('div').remove()" class="text-red-500 hover:text-red-700 ms-4 text-lg leading-none">&times;</button>
            </div>
            @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm shadow-sm">
                <div class="font-semibold mb-1 flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    {{ __('messages.fix_errors') }}
                </div>
                <ul class="{{ app()->getLocale()==='ar' ? 'me-4' : 'ms-4' }} list-disc space-y-0.5">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
            @endif
        </div>

        <!-- Page content -->
        <main class="flex-1 overflow-y-auto p-4 lg:p-6">
            @yield('content')
        </main>
    </div>

    <script>
        const sidebar  = document.getElementById('sidebar');
        const PREF_KEY = 'sidebarCollapsed';

        if (localStorage.getItem(PREF_KEY) === '1') {
            sidebar.classList.add('collapsed');
        }
        function toggleSidebar() {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem(PREF_KEY, sidebar.classList.contains('collapsed') ? '1' : '0');
        }
    </script>
    @stack('scripts')
</body>
</html>
