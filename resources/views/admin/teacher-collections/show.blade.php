@extends('layouts.admin')
@section('title', $teacherCollection->name_localized . ' — ' . __('messages.teacher_collections'))
@section('page-title', __('messages.teacher_collections'))

@section('content')
<div x-data="collectionPage()" x-init="init()">

{{-- Breadcrumb --}}
<nav class="flex items-center gap-1.5 text-sm text-gray-500 mb-4 flex-wrap">
    <a href="{{ route('admin.teacher-collections.index') }}" class="hover:text-emerald-600 transition">{{ __('messages.teacher_collections') }}</a>
    @foreach($breadcrumb as $bc)
    <svg class="w-3.5 h-3.5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <a href="{{ route('admin.teacher-collections.show', $bc) }}" class="hover:text-emerald-600 transition truncate max-w-[150px]">{{ $bc->name_localized }}</a>
    @endforeach
    <svg class="w-3.5 h-3.5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="font-semibold text-gray-700 truncate max-w-[200px]">{{ $teacherCollection->name_localized }}</span>
</nav>

{{-- Page header --}}
<div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-6">
    <div class="flex items-center gap-4">
        {{-- Collection icon badge --}}
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl shadow-sm shrink-0"
             style="background-color: {{ $teacherCollection->cover_color }}20; border: 2px solid {{ $teacherCollection->cover_color }}30;">
            {{ $teacherCollection->icon ?? '📁' }}
        </div>
        <div>
            <div class="flex items-center gap-2 flex-wrap">
                <h2 class="text-xl font-bold text-gray-800">{{ $teacherCollection->name_localized }}</h2>
                @if($teacherCollection->is_private)
                <span class="inline-flex items-center gap-1 text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    {{ __('messages.private') }}
                </span>
                @endif
                @if($teacherCollection->parent)
                <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">
                    {{ __('messages.in') }}: {{ $teacherCollection->parent->name_localized }}
                </span>
                @endif
            </div>
            @if($teacherCollection->description)
            <p class="text-sm text-gray-500 mt-1 max-w-xl">{{ $teacherCollection->description }}</p>
            @endif
            <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                <span>{{ $materials->count() }} {{ __('messages.materials') }}</span>
                @if($teacherCollection->children->count() > 0)
                <span>·</span>
                <span>{{ $teacherCollection->children->count() }} {{ __('messages.sub_collections') }}</span>
                @endif
                @if($teacherCollection->creator)
                <span>·</span>
                <span>{{ __('messages.by') }} {{ $teacherCollection->creator->name }}</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Action buttons --}}
    <div class="flex items-center gap-2 flex-wrap shrink-0">
        <a href="{{ route('admin.teacher-collections.create', ['parent_id' => $teacherCollection->id]) }}"
           class="inline-flex items-center gap-1.5 border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 text-sm px-3.5 py-2 rounded-xl shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
            {{ __('messages.add_sub_collection') }}
        </a>
        <button @click="showAddModal = true"
                class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm px-4 py-2 rounded-xl shadow transition active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            {{ __('messages.add_materials') }}
        </button>
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.outside="open = false"
                    class="p-2 border border-gray-200 bg-white rounded-xl text-gray-500 hover:bg-gray-50 shadow-sm transition">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 5a2 2 0 110-4 2 2 0 010 4zm0 5a2 2 0 110-4 2 2 0 010 4z"/></svg>
            </button>
            <div x-show="open" x-transition class="absolute end-0 top-10 z-20 bg-white rounded-xl shadow-lg border border-gray-100 py-1 w-44 text-sm">
                <a href="{{ route('admin.teacher-collections.edit', $teacherCollection) }}"
                   class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586"/></svg>
                    {{ __('messages.edit_collection') }}
                </a>
                <div class="border-t border-gray-100 my-1"></div>
                <form method="POST" action="{{ route('admin.teacher-collections.destroy', $teacherCollection) }}"
                      onsubmit="return confirm('{{ __('messages.confirm_delete_collection') }}')">
                    @csrf @method('DELETE')
                    <button class="w-full flex items-center gap-2 px-4 py-2 text-red-500 hover:bg-red-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        {{ __('messages.delete_collection') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="mb-4 flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    {{ session('success') }}
</div>
@endif

{{-- Sub-collections row --}}
@if($teacherCollection->children->count() > 0)
<div class="mb-6">
    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">{{ __('messages.sub_collections') }}</h3>
    <div class="flex flex-wrap gap-2">
        @foreach($teacherCollection->children as $sub)
        <a href="{{ route('admin.teacher-collections.show', $sub) }}"
           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border text-sm font-medium transition hover:shadow-sm"
           style="border-color: {{ $sub->cover_color }}40; color: {{ $sub->cover_color }}; background-color: {{ $sub->cover_color }}10;"
           onmouseover="this.style.backgroundColor='{{ $sub->cover_color }}20'"
           onmouseout="this.style.backgroundColor='{{ $sub->cover_color }}10'">
            <span>{{ $sub->icon ?? '📂' }}</span>
            <span>{{ $sub->name_localized }}</span>
            <span class="text-xs opacity-60 font-normal">({{ $sub->materials->count() }})</span>
        </a>
        @endforeach
        <a href="{{ route('admin.teacher-collections.create', ['parent_id' => $teacherCollection->id]) }}"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-dashed border-gray-300 text-sm text-gray-400 hover:border-emerald-400 hover:text-emerald-500 transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            {{ __('messages.add_sub_collection') }}
        </a>
    </div>
</div>
@endif

{{-- Toolbar --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex flex-wrap items-center gap-2">
        {{-- Search --}}
        <div class="relative flex-1 min-w-[180px]">
            <input type="text" id="mat-search" placeholder="{{ __('messages.search') }}…"
                   class="w-full border border-gray-200 bg-white rounded-lg ps-8 pe-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
            <svg class="w-3.5 h-3.5 text-gray-400 absolute start-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>

        {{-- Type filter --}}
        <select id="mat-type-filter" class="border border-gray-200 bg-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 min-w-[100px]">
            <option value="">{{ __('messages.all_types') }}</option>
            @foreach(\App\Models\TeacherMaterial::allTypes() as $t)
            <option value="{{ $t }}">{{ strtoupper($t) }}</option>
            @endforeach
        </select>

        {{-- View toggle --}}
        <div class="flex items-center rounded-lg border border-gray-200 overflow-hidden bg-white ms-auto">
            <button @click="viewMode = 'grid'"
                    :class="viewMode === 'grid' ? 'bg-emerald-50 text-emerald-600' : 'text-gray-400 hover:text-gray-600'"
                    class="p-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            </button>
            <button @click="viewMode = 'list'"
                    :class="viewMode === 'list' ? 'bg-emerald-50 text-emerald-600' : 'text-gray-400 hover:text-gray-600'"
                    class="p-2 transition border-s border-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            </button>
        </div>
    </div>

    {{-- Materials: Grid view --}}
    <div x-show="viewMode === 'grid'" class="p-5">
        @if($materials->isEmpty())
        <div class="py-16 text-center">
            <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <p class="text-sm text-gray-400">{{ __('messages.no_materials_in_collection') }}</p>
            <button @click="showAddModal = true"
                    class="mt-3 inline-flex items-center gap-1.5 text-sm text-emerald-600 hover:underline font-medium">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                {{ __('messages.add_materials') }}
            </button>
        </div>
        @else
        <div id="materials-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($materials as $mat)
            @php $tc = \App\Models\TeacherMaterial::typeColor($mat->material_type); @endphp
            <div class="material-card group bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden"
                 data-id="{{ $mat->id }}"
                 data-title="{{ strtolower($mat->title) }}"
                 data-type="{{ $mat->material_type }}">
                {{-- Cover --}}
                <div class="relative h-32 bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center overflow-hidden">
                    @if($mat->cover_image)
                    <img src="{{ $mat->cover_url }}" class="w-full h-full object-cover" alt="{{ $mat->title }}">
                    @else
                    <span class="text-4xl">{{ $mat->type_icon }}</span>
                    @endif
                    {{-- Type badge --}}
                    <span class="absolute top-2 start-2 text-xs font-bold px-2 py-0.5 rounded-full uppercase {{ $tc }}">
                        {{ $mat->material_type }}
                    </span>
                    {{-- Drag handle --}}
                    <div class="drag-handle absolute top-2 end-2 opacity-0 group-hover:opacity-100 transition bg-white/80 rounded-lg p-1 cursor-grab" title="{{ __('messages.drag_to_reorder') }}">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="currentColor" viewBox="0 0 24 24"><path d="M8 6a2 2 0 100-4 2 2 0 000 4zm0 6a2 2 0 100-4 2 2 0 000 4zm0 6a2 2 0 100-4 2 2 0 000 4zm8-12a2 2 0 100-4 2 2 0 000 4zm0 6a2 2 0 100-4 2 2 0 000 4zm0 6a2 2 0 100-4 2 2 0 000 4z"/></svg>
                    </div>
                </div>
                {{-- Content --}}
                <div class="p-3">
                    <h4 class="font-semibold text-gray-800 text-sm line-clamp-2 leading-snug mb-1">{{ $mat->title_localized }}</h4>
                    <div class="flex items-center gap-1.5 flex-wrap text-xs text-gray-400 mb-2">
                        @if($mat->subject) <span>{{ $mat->subject }}</span> @endif
                        @if($mat->grade_level) <span>·</span><span class="bg-amber-50 text-amber-600 px-1.5 rounded">{{ $mat->grade_level }}</span> @endif
                    </div>
                    {{-- Actions --}}
                    <div class="flex items-center gap-1.5 mt-2">
                        <a href="{{ route('admin.teacher-materials.show', $mat) }}"
                           class="flex-1 text-center text-xs py-1.5 rounded-lg bg-gray-50 border border-gray-200 text-gray-600 hover:bg-gray-100 transition">
                            {{ __('messages.view') }}
                        </a>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.outside="open = false"
                                    class="p-1.5 rounded-lg bg-gray-50 border border-gray-200 text-gray-500 hover:bg-gray-100 transition">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 5a2 2 0 110-4 2 2 0 010 4zm0 5a2 2 0 110-4 2 2 0 010 4z"/></svg>
                            </button>
                            <div x-show="open" x-transition
                                 class="absolute end-0 bottom-8 z-20 bg-white rounded-xl shadow-lg border border-gray-100 py-1 w-48 text-xs">
                                <a href="{{ route('admin.teacher-materials.edit', $mat) }}"
                                   class="flex items-center gap-2 px-3 py-2 text-gray-700 hover:bg-gray-50">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586"/></svg>
                                    {{ __('messages.edit') }}
                                </a>
                                {{-- Move to another collection --}}
                                @php $allCollections = \App\Models\TeacherCollection::where('id','!=',$teacherCollection->id)->orderBy('name')->get(); @endphp
                                @if($allCollections->count() > 0)
                                <div x-data="{ moveOpen: false }">
                                    <button @click="moveOpen = !moveOpen" class="w-full flex items-center gap-2 px-3 py-2 text-gray-700 hover:bg-gray-50">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                        {{ __('messages.move_to') }}
                                    </button>
                                    <div x-show="moveOpen" class="ps-6 py-1 border-t border-gray-50 max-h-32 overflow-y-auto">
                                        @foreach($allCollections as $tc2)
                                        <form method="POST" action="{{ route('admin.teacher-collections.move-material', [$teacherCollection, $mat]) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="target_collection_id" value="{{ $tc2->id }}">
                                            <button class="w-full text-start px-3 py-1.5 text-gray-600 hover:bg-gray-50 hover:text-emerald-600 transition truncate">
                                                {{ $tc2->icon ?? '📁' }} {{ $tc2->name_localized }}
                                            </button>
                                        </form>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('admin.teacher-collections.remove-material', [$teacherCollection, $mat]) }}"
                                      onsubmit="return confirm('{{ __('messages.confirm_remove_from_collection') }}')">
                                    @csrf @method('DELETE')
                                    <button class="w-full flex items-center gap-2 px-3 py-2 text-red-500 hover:bg-red-50">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        {{ __('messages.remove_from_collection') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Materials: List view --}}
    <div x-show="viewMode === 'list'" class="overflow-x-auto">
        @if($materials->isEmpty())
        <div class="py-16 text-center text-sm text-gray-400">{{ __('messages.no_materials_in_collection') }}</div>
        @else
        <table class="w-full text-sm" id="materials-list">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="w-8 px-4 py-3"></th>
                    <th class="px-5 py-3 text-start">{{ __('messages.material') }}</th>
                    <th class="px-5 py-3 text-start hidden md:table-cell">{{ __('messages.subject') }}</th>
                    <th class="px-5 py-3 text-center">{{ __('messages.type') }}</th>
                    <th class="px-5 py-3 text-center">{{ __('messages.status') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50" id="materials-list-body">
                @foreach($materials as $mat)
                @php $tc = \App\Models\TeacherMaterial::typeColor($mat->material_type); @endphp
                <tr class="material-card hover:bg-gray-50 transition"
                    data-id="{{ $mat->id }}"
                    data-title="{{ strtolower($mat->title) }}"
                    data-type="{{ $mat->material_type }}">
                    <td class="px-4 py-3">
                        <div class="drag-handle cursor-grab text-gray-300 hover:text-gray-500" title="{{ __('messages.drag_to_reorder') }}">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 6a2 2 0 100-4 2 2 0 000 4zm0 6a2 2 0 100-4 2 2 0 000 4zm0 6a2 2 0 100-4 2 2 0 000 4zm8-12a2 2 0 100-4 2 2 0 000 4zm0 6a2 2 0 100-4 2 2 0 000 4zm0 6a2 2 0 100-4 2 2 0 000 4z"/></svg>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-11 rounded-lg overflow-hidden shrink-0 bg-gray-50 flex items-center justify-center border border-gray-100">
                                @if($mat->cover_image)
                                <img src="{{ $mat->cover_url }}" class="w-full h-full object-cover">
                                @else
                                <span>{{ $mat->type_icon }}</span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="font-medium text-gray-800 truncate max-w-[200px]">{{ $mat->title_localized }}</div>
                                <div class="text-xs text-gray-400">{{ $mat->source }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-600 hidden md:table-cell">{{ $mat->subject ?: '—' }}</td>
                    <td class="px-5 py-3 text-center">
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full uppercase {{ $tc }}">{{ $mat->material_type }}</span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        @if($mat->status === 'active')
                        <span class="text-xs bg-emerald-50 text-emerald-600 border border-emerald-200 px-2 py-0.5 rounded-full">{{ __('messages.active') }}</span>
                        @else
                        <span class="text-xs bg-amber-50 text-amber-600 border border-amber-200 px-2 py-0.5 rounded-full">{{ __('messages.draft') }}</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('admin.teacher-materials.show', $mat) }}"
                               class="text-xs bg-gray-50 border border-gray-200 text-gray-600 hover:bg-gray-100 px-2.5 py-1 rounded-lg transition">
                                {{ __('messages.view') }}
                            </a>
                            <a href="{{ route('admin.teacher-materials.edit', $mat) }}"
                               class="text-xs bg-emerald-50 border border-emerald-200 text-emerald-600 hover:bg-emerald-100 px-2.5 py-1 rounded-lg transition">
                                {{ __('messages.edit') }}
                            </a>
                            <form method="POST" action="{{ route('admin.teacher-collections.remove-material', [$teacherCollection, $mat]) }}"
                                  onsubmit="return confirm('{{ __('messages.confirm_remove_from_collection') }}')">
                                @csrf @method('DELETE')
                                <button class="text-xs bg-red-50 border border-red-200 text-red-500 hover:bg-red-100 px-2.5 py-1 rounded-lg transition">
                                    {{ __('messages.remove') }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

{{-- ═══════════════════════ ADD MATERIALS MODAL ═══════════════════════ --}}
<div x-show="showAddModal"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
     @keydown.escape.window="showAddModal = false">
    <div x-show="showAddModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[80vh] flex flex-col"
         @click.stop>
        {{-- Modal header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-bold text-gray-800">{{ __('messages.add_materials_to_collection') }}</h3>
            <button @click="showAddModal = false" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        {{-- Search in modal --}}
        <div class="px-6 py-3 border-b border-gray-50">
            <div class="relative">
                <input type="text" x-model="modalSearch" placeholder="{{ __('messages.search') }}…"
                       class="w-full border border-gray-200 rounded-lg ps-8 pe-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                <svg class="w-3.5 h-3.5 text-gray-400 absolute start-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>
        {{-- Material list --}}
        <div class="flex-1 overflow-y-auto px-6 py-3 space-y-1.5">
            @forelse($availableMaterials as $avail)
            @php $atc = \App\Models\TeacherMaterial::typeColor($avail->material_type); @endphp
            <label class="avail-material flex items-center gap-3 p-3 rounded-xl border border-transparent hover:border-emerald-200 hover:bg-emerald-50/50 cursor-pointer transition"
                   data-title="{{ strtolower($avail->title) }}">
                <input type="checkbox" name="material_ids[]" value="{{ $avail->id }}"
                       x-model="selectedMaterials"
                       class="w-4 h-4 rounded text-emerald-600 border-gray-300 focus:ring-emerald-400">
                <div class="w-8 h-10 rounded-lg overflow-hidden shrink-0 bg-gray-50 flex items-center justify-center border border-gray-100 text-sm">
                    @if($avail->cover_image)
                    <img src="{{ $avail->cover_url }}" class="w-full h-full object-cover">
                    @else
                    {{ $avail->type_icon }}
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-medium text-sm text-gray-800 truncate">{{ $avail->title_localized }}</div>
                    <div class="text-xs text-gray-400">
                        <span class="font-bold {{ $atc }} px-1.5 py-0.5 rounded-full text-xs">{{ strtoupper($avail->material_type) }}</span>
                        @if($avail->subject) · {{ $avail->subject }} @endif
                    </div>
                </div>
            </label>
            @empty
            <div class="py-8 text-center text-sm text-gray-400">{{ __('messages.all_materials_in_collection') }}</div>
            @endforelse
        </div>
        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between gap-3 bg-gray-50 rounded-b-2xl">
            <span class="text-sm text-gray-500" x-text="selectedMaterials.length + ' {{ __('messages.selected') }}'"></span>
            <div class="flex items-center gap-2">
                <button @click="showAddModal = false; selectedMaterials = []"
                        class="border border-gray-200 bg-white text-gray-600 text-sm px-4 py-2 rounded-xl hover:bg-gray-50 transition">
                    {{ __('messages.cancel') }}
                </button>
                <form id="add-materials-form" method="POST"
                      action="{{ route('admin.teacher-collections.add-materials', $teacherCollection) }}">
                    @csrf
                    <template x-for="id in selectedMaterials" :key="id">
                        <input type="hidden" name="material_ids[]" :value="id">
                    </template>
                    <button type="submit" :disabled="selectedMaterials.length === 0"
                            :class="selectedMaterials.length === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-emerald-700 active:scale-95'"
                            class="bg-emerald-600 text-white font-semibold text-sm px-5 py-2 rounded-xl shadow transition">
                        {{ __('messages.add_selected') }} (<span x-text="selectedMaterials.length"></span>)
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

</div>{{-- /x-data --}}
@endsection

@push('scripts')
<script>
function collectionPage() {
    return {
        viewMode: localStorage.getItem('col-view') || 'grid',
        showAddModal: false,
        selectedMaterials: [],
        modalSearch: '',

        init() {
            this.$watch('viewMode', v => localStorage.setItem('col-view', v));
            this.$watch('modalSearch', v => this.filterModal(v));
            this.initSearch();
            this.initDragSort();
        },

        filterModal(term) {
            document.querySelectorAll('.avail-material').forEach(el => {
                const title = el.dataset.title || '';
                el.style.display = title.includes(term.toLowerCase()) ? '' : 'none';
            });
        },

        initSearch() {
            const searchInput = document.getElementById('mat-search');
            const typeFilter  = document.getElementById('mat-type-filter');

            const filter = () => {
                const term = searchInput.value.toLowerCase();
                const type = typeFilter.value;
                document.querySelectorAll('.material-card').forEach(card => {
                    const titleMatch = !term || (card.dataset.title || '').includes(term);
                    const typeMatch  = !type || card.dataset.type === type;
                    card.style.display = (titleMatch && typeMatch) ? '' : 'none';
                });
            };

            searchInput?.addEventListener('input', filter);
            typeFilter?.addEventListener('change', filter);
        },

        initDragSort() {
            const gridEl = document.getElementById('materials-grid');
            const listEl = document.getElementById('materials-list-body');

            [gridEl, listEl].forEach(container => {
                if (!container) return;
                let dragging = null;

                container.querySelectorAll('.drag-handle').forEach(h => {
                    h.addEventListener('mousedown', () => {
                        h.closest('.material-card,[data-id]').setAttribute('draggable', true);
                    });
                });

                container.addEventListener('dragstart', e => {
                    dragging = e.target.closest('[data-id]');
                    setTimeout(() => dragging?.classList.add('opacity-40'), 0);
                });
                container.addEventListener('dragend', e => {
                    const el = e.target.closest('[data-id]');
                    el?.classList.remove('opacity-40');
                    el?.setAttribute('draggable', false);
                    dragging = null;
                    this.saveReorder(container);
                });
                container.addEventListener('dragover', e => {
                    e.preventDefault();
                    const target = e.target.closest('[data-id]');
                    if (target && target !== dragging) {
                        const rect = target.getBoundingClientRect();
                        const isAfter = container.tagName === 'TBODY'
                            ? e.clientY > rect.top + rect.height / 2
                            : e.clientX > rect.left + rect.width / 2;
                        if (isAfter) container.insertBefore(dragging, target.nextSibling);
                        else container.insertBefore(dragging, target);
                    }
                });
            });
        },

        saveReorder(container) {
            const order = [...container.querySelectorAll('[data-id]')].map(el => el.dataset.id);
            fetch('{{ route('admin.teacher-collections.reorder-materials', $teacherCollection) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ order }),
            });
        },
    };
}
</script>
@endpush
