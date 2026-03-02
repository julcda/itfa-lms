@extends('layouts.lms')
@section('title', __('messages.e_library'))

@push('styles')
<style>
/* ── Book card ── */
.book-card { transition: transform .18s ease, box-shadow .18s ease; }
.book-card:hover { transform: translateY(-5px); box-shadow: 0 16px 40px rgba(0,0,0,.12), 0 0 0 2px rgba(16,185,129,.2); }

/* ── Cover gradients ── */
.cvr-0{background:linear-gradient(135deg,#667eea,#764ba2)}
.cvr-1{background:linear-gradient(135deg,#f093fb,#f5576c)}
.cvr-2{background:linear-gradient(135deg,#4facfe,#00f2fe)}
.cvr-3{background:linear-gradient(135deg,#43e97b,#38f9d7)}
.cvr-4{background:linear-gradient(135deg,#fa709a,#fee140)}
.cvr-5{background:linear-gradient(135deg,#a18cd1,#fbc2eb)}
.cvr-6{background:linear-gradient(135deg,#fda085,#f6d365)}
.cvr-7{background:linear-gradient(135deg,#89f7fe,#66a6ff)}

/* ── Type badges ── */
.tb{font-size:.6rem;font-weight:800;padding:.15rem .45rem;border-radius:999px;text-transform:uppercase;letter-spacing:.04em}
.tb-pdf      {background:#fee2e2;color:#b91c1c}
.tb-video    {background:#dbeafe;color:#1d4ed8}
.tb-audio    {background:#fef3c7;color:#92400e}
.tb-epub     {background:#ede9fe;color:#6d28d9}
.tb-doc      {background:#dcfce7;color:#166534}
.tb-external {background:#e0f2fe;color:#0369a1}
.tb-other    {background:#f3f4f6;color:#374151}

/* ── Sidebar filter links ── */
.fil{display:flex;align-items:center;justify-content:space-between;gap:.5rem;padding:.3rem .6rem;border-radius:.5rem;font-size:.78rem;font-weight:500;color:#374151;transition:background .12s,color .12s;cursor:pointer;text-decoration:none}
.fil:hover{background:#ecfdf5;color:#065f46}
.fil.on{background:#d1fae5;color:#065f46;font-weight:700}
.fil .cnt{font-size:.68rem;color:#9ca3af;flex-shrink:0}
.fil.on .cnt{color:#6ee7b7}
</style>
@endpush

@section('content')

{{-- ═══════ HEADER BANNER ═══════ --}}
<div style="background:linear-gradient(135deg,#064e3b 0%,#065f46 50%,#047857 100%);border-bottom:1px solid #059669">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            {{-- Title --}}
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-black text-white leading-tight">{{ __('messages.e_library') }}</h1>
                    <p class="text-emerald-200 text-xs">{{ __('messages.library_subtitle') }}</p>
                </div>
            </div>

            {{-- Stats chips --}}
            <div class="flex items-center gap-3">
                <div class="bg-white/10 backdrop-blur rounded-xl px-4 py-2 text-center border border-white/15">
                    <div class="text-xl font-black text-white">{{ number_format($stats['total']) }}</div>
                    <div class="text-emerald-200 text-[10px] uppercase tracking-wide">{{ __('messages.total_books') }}</div>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-xl px-4 py-2 text-center border border-white/15">
                    <div class="text-xl font-black text-white">{{ number_format($stats['downloads']) }}</div>
                    <div class="text-emerald-200 text-[10px] uppercase tracking-wide">{{ __('messages.downloads') }}</div>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-xl px-4 py-2 text-center border border-white/15">
                    <div class="text-xl font-black text-white">{{ number_format($stats['categories']) }}</div>
                    <div class="text-emerald-200 text-[10px] uppercase tracking-wide">{{ __('messages.categories') }}</div>
                </div>
            </div>
        </div>

        {{-- ── Search bar ── --}}
        <form method="GET" action="{{ route('student.library.index') }}"
              class="mt-4 flex flex-col sm:flex-row gap-2">
            <input type="hidden" name="view"        value="{{ request('view','grid') }}">
            <input type="hidden" name="sort"        value="{{ request('sort','latest') }}">
            <input type="hidden" name="file_type"   value="{{ request('file_type','') }}">
            <input type="hidden" name="grade_level" value="{{ request('grade_level','') }}">

            {{-- Search input --}}
            <div class="relative flex-1">
                <span class="absolute inset-y-0 start-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="{{ __('messages.search_books') }}"
                       class="w-full bg-white border border-white/40 rounded-xl ps-9 pe-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-300 transition">
            </div>

            {{-- Category dropdown --}}
            <div class="relative">
                <select name="category_id"
                        class="appearance-none w-full sm:w-52 bg-white border border-white/40 rounded-xl px-4 pe-9 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 transition cursor-pointer">
                    <option value="" class="text-gray-800 bg-white">{{ __('messages.all_categories') }}</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" class="text-gray-800 bg-white"
                            {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name_localized }}
                        @if($cat->books_count) ({{ $cat->books_count }}) @endif
                    </option>
                    @endforeach
                </select>
                <span class="pointer-events-none absolute inset-y-0 end-3 flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </span>
            </div>

            {{-- Search button --}}
            <button type="submit"
                    class="bg-white text-emerald-700 font-bold text-sm px-6 py-2.5 rounded-xl hover:bg-emerald-50 active:scale-95 transition whitespace-nowrap shadow-sm">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    {{ __('messages.search') }}
                </span>
            </button>

            {{-- Clear if active --}}
            @if(request()->hasAny(['search','category_id']))
            <a href="{{ route('student.library.index', array_filter(request()->except(['search','category_id','page']))) }}"
               class="flex items-center gap-1.5 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition whitespace-nowrap">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ __('messages.clear') }}
            </a>
            @endif
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">
    <div class="flex gap-5 items-start">

        {{-- ═══════ SIDEBAR ═══════ --}}
        <aside class="w-52 shrink-0 hidden lg:flex flex-col gap-3">

            {{-- Search --}}
            <form method="GET" action="{{ route('student.library.index') }}" class="bg-white rounded-xl border border-gray-200 shadow-sm p-3">
                <input type="hidden" name="view"        value="{{ request('view','grid') }}">
                <input type="hidden" name="sort"        value="{{ request('sort','latest') }}">
                <input type="hidden" name="category_id" value="{{ request('category_id','') }}">
                <input type="hidden" name="file_type"   value="{{ request('file_type','') }}">
                <input type="hidden" name="grade_level" value="{{ request('grade_level','') }}">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="{{ __('messages.search_books') }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 pe-8 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-white transition">
                    <button type="submit" class="absolute end-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-emerald-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </div>
            </form>

            {{-- Category filter --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-3 py-2.5 border-b border-gray-100 bg-gray-50">
                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ __('messages.category') }}</span>
                </div>
                <div class="p-2 space-y-0.5">
                    <a href="{{ route('student.library.index', array_merge(request()->except(['category_id','page']), ['view'=>request('view','grid')])) }}"
                       class="fil {{ !request('category_id') ? 'on' : '' }}">
                        <span>{{ __('messages.all_categories') }}</span>
                        <span class="cnt">{{ $stats['total'] }}</span>
                    </a>
                    @foreach($categories as $cat)
                    <a href="{{ route('student.library.index', array_merge(request()->except(['category_id','page']), ['category_id'=>$cat->id,'view'=>request('view','grid')])) }}"
                       class="fil {{ request('category_id') == $cat->id ? 'on' : '' }}">
                        <span class="truncate">{{ $cat->name_localized }}</span>
                        @if($cat->books_count)<span class="cnt">{{ $cat->books_count }}</span>@endif
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Grade Level filter --}}
            @if($gradeLevels->isNotEmpty())
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-3 py-2.5 border-b border-gray-100 bg-gray-50">
                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ __('messages.grade_level') }}</span>
                </div>
                <div class="p-2 space-y-0.5">
                    <a href="{{ route('student.library.index', array_merge(request()->except(['grade_level','page']), ['view'=>request('view','grid')])) }}"
                       class="fil {{ !request('grade_level') ? 'on' : '' }}">
                        <span>{{ __('messages.all') }}</span>
                    </a>
                    @foreach($gradeLevels as $gl)
                    <a href="{{ route('student.library.index', array_merge(request()->except(['grade_level','page']), ['grade_level'=>$gl,'view'=>request('view','grid')])) }}"
                       class="fil {{ request('grade_level') === $gl ? 'on' : '' }}">
                        {{ $gl }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- File Type filter --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-3 py-2.5 border-b border-gray-100 bg-gray-50">
                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ __('messages.file_type') }}</span>
                </div>
                <div class="p-2 space-y-0.5">
                    @php $typeOpts=[''=> __('messages.all_types'),'pdf'=>'PDF','video'=>'Video','audio'=>'Audio','epub'=>'EPUB','doc'=>'DOC','external'=>'External','other'=>'Other']; @endphp
                    @foreach($typeOpts as $val=>$lbl)
                    <a href="{{ route('student.library.index', array_merge(request()->except(['file_type','page']), ['file_type'=>$val,'view'=>request('view','grid')])) }}"
                       class="fil {{ request('file_type','') === $val ? 'on' : '' }}">
                        <span>{{ $lbl }}</span>
                        @if($val!=='' && isset($typeCounts[$val]))<span class="cnt">{{ $typeCounts[$val] }}</span>@endif
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Clear filters --}}
            @if(request()->hasAny(['search','category_id','file_type','grade_level']))
            <a href="{{ route('student.library.index') }}"
               class="block text-center text-xs text-red-600 font-semibold py-2 border border-red-200 rounded-xl bg-red-50 hover:bg-red-100 transition">
                ✕ {{ __('messages.clear_filters') }}
            </a>
            @endif
        </aside>

        {{-- ═══════ MAIN ═══════ --}}
        <div class="flex-1 min-w-0">

            {{-- Toolbar --}}
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">

                {{-- Active filter chips + count --}}
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-sm text-gray-500 font-medium">
                        <span class="text-gray-900 font-bold">{{ $books->total() }}</span> {{ __('messages.found') }}
                    </span>
                    @if(request('search'))
                        <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs px-2.5 py-0.5 rounded-full font-medium">
                            "{{ request('search') }}"
                            <a href="{{ request()->fullUrlWithoutQuery(['search','page']) }}" class="ms-1 text-emerald-400 hover:text-red-500 font-bold">&times;</a>
                        </span>
                    @endif
                    @if(request('grade_level'))
                        <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs px-2.5 py-0.5 rounded-full font-medium">
                            {{ request('grade_level') }}
                            <a href="{{ request()->fullUrlWithoutQuery(['grade_level','page']) }}" class="ms-1 text-amber-400 hover:text-red-500 font-bold">&times;</a>
                        </span>
                    @endif
                    @if(request('category_id') && ($selCat=$categories->firstWhere('id',request('category_id'))))
                        <span class="inline-flex items-center gap-1 bg-violet-50 text-violet-700 border border-violet-200 text-xs px-2.5 py-0.5 rounded-full font-medium">
                            {{ $selCat->name_localized }}
                            <a href="{{ request()->fullUrlWithoutQuery(['category_id','page']) }}" class="ms-1 text-violet-400 hover:text-red-500 font-bold">&times;</a>
                        </span>
                    @endif
                    @if(request('file_type'))
                        <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 border border-blue-200 text-xs px-2.5 py-0.5 rounded-full font-medium uppercase">
                            {{ request('file_type') }}
                            <a href="{{ request()->fullUrlWithoutQuery(['file_type','page']) }}" class="ms-1 text-blue-400 hover:text-red-500 font-bold">&times;</a>
                        </span>
                    @endif
                </div>

                {{-- Controls: sort + view toggle --}}
                <div class="flex items-center gap-2">
                    {{-- Sort --}}
                    <form method="GET" action="{{ route('student.library.index') }}">
                        @foreach(request()->except(['sort','page']) as $k=>$v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <select name="sort" onchange="this.form.submit()"
                                class="border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs text-gray-600 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                            <option value="latest"    {{ request('sort','latest')==='latest'   ?'selected':'' }}>{{ __('messages.sort_latest') }}</option>
                            <option value="popular"   {{ request('sort')==='popular'          ?'selected':'' }}>{{ __('messages.sort_popular') }}</option>
                            <option value="downloads" {{ request('sort')==='downloads'        ?'selected':'' }}>{{ __('messages.sort_downloads') }}</option>
                            <option value="title"     {{ request('sort')==='title'            ?'selected':'' }}>{{ __('messages.sort_title') }}</option>
                        </select>
                    </form>
                    {{-- Grid / List --}}
                    <div class="flex bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                        <a href="{{ request()->fullUrlWithQuery(['view'=>'grid']) }}"
                           class="px-2.5 py-1.5 {{ request('view','grid')==='grid' ? 'bg-emerald-500 text-white' : 'text-gray-400 hover:bg-gray-50' }} transition"
                           title="Grid view">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['view'=>'list']) }}"
                           class="px-2.5 py-1.5 border-l border-gray-200 {{ request('view')==='list' ? 'bg-emerald-500 text-white' : 'text-gray-400 hover:bg-gray-50' }} transition"
                           title="List view">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h7"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- ─── Empty state ─── --}}
            @if($books->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm flex flex-col items-center justify-center py-24 text-center">
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13"/></svg>
                </div>
                <h3 class="text-base font-semibold text-gray-700 mb-1">{{ __('messages.no_books_found') }}</h3>
                <p class="text-sm text-gray-400 mb-5">{{ __('messages.try_different_filters') }}</p>
                <a href="{{ route('student.library.index') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-full text-sm font-semibold transition">{{ __('messages.browse_all') }}</a>
            </div>

            {{-- ─── List view ─── --}}
            @elseif(request('view')==='list')
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-start px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide w-8">#</th>
                            <th class="text-start px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('messages.book') }}</th>
                            <th class="text-start px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden md:table-cell">{{ __('messages.category') }}</th>
                            <th class="text-start px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden sm:table-cell">{{ __('messages.grade_level') }}</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('messages.type') }}</th>
                            <th class="px-4 py-3 w-20"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                    @foreach($books as $i => $book)
                    @php $ci = $book->id % 8; @endphp
                    <tr class="hover:bg-emerald-50/40 transition group">
                        <td class="px-4 py-3 text-xs text-gray-300 font-mono">{{ (($books->currentPage()-1)*$books->perPage())+$i+1 }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-14 rounded-lg overflow-hidden shrink-0 cvr-{{ $ci }}">
                                    @if($book->cover_image)
                                        <img src="{{ $book->cover_url }}" alt="" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13"/></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-800 truncate max-w-[200px] group-hover:text-emerald-700 transition">{{ $book->title_localized }}</p>
                                    <p class="text-xs text-gray-400 truncate mt-0.5">{{ $book->author }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell">
                            @if($book->category)
                            <a href="{{ route('student.library.index', array_merge(request()->except(['category_id','page']), ['category_id'=>$book->category->id,'view'=>request('view','grid')])) }}"
                               class="text-xs text-emerald-600 hover:text-emerald-800 hover:underline font-medium">
                                {{ $book->category->name_localized }}
                            </a>
                            @else
                            <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 hidden sm:table-cell">
                            @if($book->grade_level)
                            <span class="text-xs bg-amber-50 text-amber-700 border border-amber-200 px-2 py-0.5 rounded-full font-semibold">{{ $book->grade_level }}</span>
                            @else
                            <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="tb tb-{{ strtolower($book->file_type) }}">{{ strtoupper($book->file_type) }}</span>
                        </td>
                        <td class="px-4 py-3 text-end">
                            <a href="{{ route('student.library.show', $book) }}"
                               class="inline-flex items-center gap-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-full px-3 py-1 text-xs font-semibold transition whitespace-nowrap">
                                {{ __('messages.open') }}
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ─── Grid view ─── --}}
            @else
            <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-3">
                @foreach($books as $book)
                @php $ci = $book->id % 8; @endphp
                <div class="book-card group bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
                    {{-- Cover (links to detail) --}}
                    <a href="{{ route('student.library.show', $book) }}" class="block relative overflow-hidden cvr-{{ $ci }}" style="padding-top:115%">
                        @if($book->cover_image)
                            <img src="{{ $book->cover_url }}" alt="{{ $book->title_localized }}"
                                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="absolute inset-0 flex flex-col items-center justify-center gap-1 p-2">
                                <svg class="w-7 h-7 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13"/></svg>
                                <p class="text-white/80 text-[9px] font-semibold text-center leading-tight line-clamp-3">{{ $book->title_localized }}</p>
                            </div>
                        @endif
                        {{-- Top badges --}}
                        <div class="absolute top-1.5 end-1.5">
                            <span class="tb tb-{{ strtolower($book->file_type) }}">{{ strtoupper($book->file_type) }}</span>
                        </div>
                        @if($book->grade_level)
                        <div class="absolute top-1.5 start-1.5">
                            <span class="text-[8px] font-bold px-1 py-0.5 rounded bg-black/40 text-white backdrop-blur-sm">{{ $book->grade_level }}</span>
                        </div>
                        @endif
                        {{-- Hover overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center pb-2">
                            <span class="bg-white text-emerald-700 font-bold text-[10px] px-2.5 py-1 rounded-full shadow-lg">
                                {{ __('messages.view_details') }}
                            </span>
                        </div>
                    </a>
                    {{-- Info --}}
                    <div class="p-2 flex-1 flex flex-col">
                        <a href="{{ route('student.library.show', $book) }}" class="text-[11px] font-bold text-gray-800 line-clamp-2 leading-snug flex-1 hover:text-emerald-700 transition">{{ $book->title_localized }}</a>
                        @if($book->author)
                        <p class="text-[10px] text-gray-400 mt-0.5 truncate">{{ $book->author }}</p>
                        @endif
                        @if($book->category)
                        <a href="{{ route('student.library.index', array_merge(request()->except(['category_id','page']), ['category_id'=>$book->category->id,'view'=>request('view','grid')])) }}"
                           class="text-[10px] text-emerald-600 hover:text-emerald-800 font-semibold truncate mt-0.5 hover:underline" title="{{ __('messages.filter_by') }} {{ $book->category->name_localized }}">
                            {{ $book->category->name_localized }}
                        </a>
                        @endif
                        <div class="flex items-center gap-2 mt-1.5 pt-1.5 border-t border-gray-100 text-[10px] text-gray-400">
                            <span class="flex items-center gap-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                {{ number_format($book->view_count) }}
                            </span>
                            <span class="flex items-center gap-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                {{ number_format($book->download_count) }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Pagination --}}
            @if($books->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $books->withQueryString()->links() }}
            </div>
            @endif

        </div>{{-- /main --}}
    </div>{{-- /flex --}}
</div>
@endsection