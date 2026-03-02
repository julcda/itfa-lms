<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', school_name()) — {{ setting('school_tagline', __('messages.lms')) }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        [dir="rtl"] { font-family: 'Noto Kufi Arabic', sans-serif; }
        [dir="ltr"] { font-family: 'Inter', sans-serif; }
        .animate-fade-in { animation: fadeIn 0.3s ease-in; }
        @keyframes fadeIn { from { opacity:0; transform: translateY(-10px); } to { opacity:1; transform: translateY(0); } }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    <!-- Navigation -->
    <nav class="bg-emerald-800 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    @php $schoolLogo = setting('school_logo'); @endphp
                    @if($schoolLogo)
                        <img src="{{ Storage::disk('public')->url($schoolLogo) }}" alt="logo" class="h-10 w-10 rounded-full object-contain bg-white p-0.5">
                    @else
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center font-bold text-emerald-800 text-sm">{{ strtoupper(substr(setting('school_short_name','S'), 0, 2)) }}</div>
                    @endif
                    <div class="hidden sm:block">
                        <div class="font-bold text-base leading-tight">{{ school_name() }}</div>
                        <div class="text-emerald-200 text-xs">{{ app()->getLocale()==='ar' ? setting('school_tagline_ar', __('messages.lms')) : setting('school_tagline', __('messages.lms')) }}</div>
                    </div>
                </a>

                <div class="hidden md:flex items-center gap-5 text-sm">
                    <a href="{{ route('home') }}" class="hover:text-emerald-200 transition">{{ __('messages.home') }}</a>
                    @auth
                        @hasrole('admin|teacher')
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-emerald-200 transition">{{ __('messages.dashboard') }}</a>
                            <a href="{{ route('admin.courses.index') }}" class="hover:text-emerald-200 transition">{{ __('messages.courses') }}</a>
                            <a href="{{ route('admin.books.index') }}" class="hover:text-emerald-200 transition">{{ __('messages.e_library') }}</a>
                        @endhasrole
                        @hasrole('student')
                            <a href="{{ route('student.dashboard') }}" class="hover:text-emerald-200 transition">{{ __('messages.dashboard') }}</a>
                            <a href="{{ route('student.courses.index') }}" class="hover:text-emerald-200 transition">{{ __('messages.courses') }}</a>
                            <a href="{{ route('student.library.index') }}" class="hover:text-emerald-200 transition">{{ __('messages.library') }}</a>
                        @endhasrole
                    @endauth
                </div>

                <div class="flex items-center gap-3">
                    <!-- Locale switcher -->
                    <div class="flex gap-1">
                        <a href="{{ route('locale.switch', 'ar') }}" class="px-2 py-1 rounded text-xs {{ app()->getLocale()==='ar' ? 'bg-white text-emerald-800 font-bold' : 'text-emerald-200 hover:text-white' }} transition">عربي</a>
                        <a href="{{ route('locale.switch', 'en') }}" class="px-2 py-1 rounded text-xs {{ app()->getLocale()==='en' ? 'bg-white text-emerald-800 font-bold' : 'text-emerald-200 hover:text-white' }} transition">EN</a>
                    </div>
                    @auth
                        <div class="relative group">
                            <button class="flex items-center gap-2 bg-emerald-700 rounded-full px-3 py-1.5 text-sm hover:bg-emerald-600 transition">
                                <div class="w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center text-xs font-bold">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
                                <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                            </button>
                            <div class="absolute {{ app()->getLocale()==='ar' ? 'left-0' : 'right-0' }} top-full mt-1 w-48 bg-white rounded-lg shadow-xl border border-gray-100 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none group-hover:pointer-events-auto z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">{{ __('messages.profile') }}</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">{{ __('messages.logout') }}</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="bg-white text-emerald-800 px-4 py-1.5 rounded-full text-sm font-semibold hover:bg-emerald-50 transition">{{ __('messages.login') }}</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div id="flash-s" class="fixed top-20 {{ app()->getLocale()==='ar' ? 'left-4' : 'right-4' }} z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2 max-w-sm animate-fade-in cursor-pointer" onclick="this.remove()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div id="flash-e" class="fixed top-20 {{ app()->getLocale()==='ar' ? 'left-4' : 'right-4' }} z-50 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2 max-w-sm animate-fade-in cursor-pointer" onclick="this.remove()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <main class="min-h-screen">
        @yield('content')
    </main>

    <footer class="bg-emerald-900 text-emerald-100 mt-16">
        <div class="max-w-7xl mx-auto px-4 py-8 text-center">
            <p class="font-semibold text-lg">{{ school_name() }}</p>
            <p class="text-emerald-300 text-sm mt-1">{{ app()->getLocale()==='ar' ? setting('school_tagline_ar', __('messages.lms')) : setting('school_tagline', __('messages.lms')) }} &copy; {{ date('Y') }}</p>
        </div>
    </footer>

    @stack('scripts')
    <script>
        ['flash-s','flash-e'].forEach(id => { const el=document.getElementById(id); if(el) setTimeout(()=>el.style.opacity='0',5000); });
    </script>
</body>
</html>
