@extends('layouts.lms')

@section('title', __('messages.home'))

@section('content')
{{-- Hero --}}
<section class="bg-gradient-to-br from-emerald-800 to-emerald-600 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ school_name() }}</h1>
        <p class="text-emerald-100 text-lg mb-8 max-w-2xl mx-auto">{{ __('messages.hero_subtitle') }}</p>
        <div class="flex flex-wrap justify-center gap-4">
            @guest
                <a href="{{ route('register') }}" class="bg-white text-emerald-800 px-8 py-3 rounded-full font-bold hover:bg-emerald-50 transition shadow-lg">{{ __('messages.get_started') }}</a>
                <a href="{{ route('login') }}" class="border-2 border-white text-white px-8 py-3 rounded-full font-semibold hover:bg-white hover:text-emerald-800 transition">{{ __('messages.login') }}</a>
            @else
                @hasrole('admin|teacher')
                    <a href="{{ route('admin.dashboard') }}" class="bg-white text-emerald-800 px-8 py-3 rounded-full font-bold hover:bg-emerald-50 transition shadow-lg">{{ __('messages.go_to_dashboard') }}</a>
                @else
                    <a href="{{ route('student.dashboard') }}" class="bg-white text-emerald-800 px-8 py-3 rounded-full font-bold hover:bg-emerald-50 transition shadow-lg">{{ __('messages.go_to_dashboard') }}</a>
                @endhasrole
            @endguest
        </div>
    </div>
</section>

{{-- Stats bar --}}
<section class="bg-white border-b border-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-3 md:grid-cols-6 gap-6 text-center">
        <div>
            <div class="text-2xl font-bold text-emerald-700">500+</div>
            <div class="text-gray-500 text-sm">{{ __('messages.students') }}</div>
        </div>
        <div>
            <div class="text-2xl font-bold text-emerald-700">50+</div>
            <div class="text-gray-500 text-sm">{{ __('messages.courses') }}</div>
        </div>
        <div>
            <div class="text-2xl font-bold text-emerald-700">200+</div>
            <div class="text-gray-500 text-sm">{{ __('messages.books') }}</div>
        </div>
        <div>
            <div class="text-2xl font-bold text-emerald-700">20+</div>
            <div class="text-gray-500 text-sm">{{ __('messages.teachers') }}</div>
        </div>
        <div>
            <div class="text-2xl font-bold text-emerald-700">1000+</div>
            <div class="text-gray-500 text-sm">{{ __('messages.certificates') }}</div>
        </div>
        <div>
            <div class="text-2xl font-bold text-emerald-700">100%</div>
            <div class="text-gray-500 text-sm">{{ __('messages.online') }}</div>
        </div>
    </div>
</section>

{{-- Featured Courses --}}
<section class="max-w-7xl mx-auto px-4 py-14">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-bold text-gray-800">{{ __('messages.featured_courses') }}</h2>
        @auth
            <a href="{{ auth()->user()->hasRole('student') ? route('student.courses.index') : route('admin.courses.index') }}" class="text-emerald-700 hover:underline text-sm font-medium">{{ __('messages.view_all') }} →</a>
        @endauth
    </div>
    @if($featuredCourses->isEmpty())
        <div class="text-center py-10 text-gray-400">{{ __('messages.no_courses_yet') }}</div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredCourses as $course)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group">
                <div class="h-44 bg-gradient-to-br from-emerald-100 to-emerald-200 relative overflow-hidden">
                    @if($course->thumbnail)
                        <img src="{{ $course->thumbnail_url }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="flex items-center justify-center h-full">
                            <svg class="w-14 h-14 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                    @endif
                    <span class="absolute top-3 {{ app()->getLocale()==='ar' ? 'left-3' : 'right-3' }} bg-emerald-600 text-white text-xs px-2 py-1 rounded-full">{{ __('messages.level_'.$course->level) }}</span>
                </div>
                <div class="p-5">
                    <h3 class="font-semibold text-gray-800 mb-1 line-clamp-2">{{ $course->title_localized }}</h3>
                    <p class="text-gray-500 text-sm mb-3 line-clamp-2">{{ Str::limit(strip_tags($course->description), 80) }}</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center text-xs font-bold text-emerald-700">{{ strtoupper(substr($course->teacher->name ?? 'T', 0, 1)) }}</div>
                            <span class="text-xs text-gray-500">{{ $course->teacher->name ?? '-' }}</span>
                        </div>
                        <span class="text-xs text-gray-400">{{ $course->lessons_count ?? 0 }} {{ __('messages.lessons') }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</section>

{{-- Latest Books --}}
<section class="bg-gray-50 py-14">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-800">{{ __('messages.latest_books') }}</h2>
            @auth
                <a href="{{ auth()->user()->hasRole('student') ? route('student.library.index') : route('admin.books.index') }}" class="text-emerald-700 hover:underline text-sm font-medium">{{ __('messages.view_all') }} →</a>
            @endauth
        </div>
        @if($latestBooks->isEmpty())
            <div class="text-center py-10 text-gray-400">{{ __('messages.no_books_yet') }}</div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($latestBooks as $book)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                    <div class="h-36 bg-gradient-to-br from-amber-100 to-amber-200 flex items-center justify-center">
                        @if($book->cover_image)
                            <img src="{{ $book->cover_url }}" alt="" class="h-full w-full object-cover">
                        @else
                            <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        @endif
                    </div>
                    <div class="p-3">
                        <p class="text-sm font-medium text-gray-800 line-clamp-2">{{ $book->title_localized }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $book->author }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- Features section --}}
<section class="max-w-7xl mx-auto px-4 py-14">
    <h2 class="text-2xl font-bold text-gray-800 text-center mb-12">{{ __('messages.why_itfa') }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach([
            ['icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'title' => 'feature_courses_title', 'desc' => 'feature_courses_desc'],
            ['icon' => 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z', 'title' => 'feature_library_title', 'desc' => 'feature_library_desc'],
            ['icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z', 'title' => 'feature_certs_title', 'desc' => 'feature_certs_desc'],
        ] as $f)
        <div class="text-center">
            <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $f['icon'] }}"/></svg>
            </div>
            <h3 class="font-bold text-gray-800 mb-2">{{ __('messages.'.$f['title']) }}</h3>
            <p class="text-gray-500 text-sm">{{ __('messages.'.$f['desc']) }}</p>
        </div>
        @endforeach
    </div>
</section>
@endsection
