@extends('layouts.admin')
@section('title', __('messages.teacher_collections'))
@section('page-title', __('messages.teacher_collections'))

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">{{ __('messages.teacher_collections') }}</h2>
        <p class="text-sm text-gray-500 mt-0.5">{{ __('messages.teacher_collections_subtitle') }}</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.teacher-materials.index') }}"
           class="inline-flex items-center gap-2 border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 text-sm px-4 py-2 rounded-xl shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            {{ __('messages.all_materials') }}
        </a>
        <a href="{{ route('admin.teacher-collections.create') }}"
           class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm px-5 py-2 rounded-xl shadow transition active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            {{ __('messages.new_collection') }}
        </a>
    </div>
</div>

{{-- Stats bar --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
    @foreach([
        ['label' => __('messages.collections'), 'value' => $stats['collections'], 'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z', 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50'],
        ['label' => __('messages.sub_collections'), 'value' => $stats['sub_collections'], 'icon' => 'M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50'],
        ['label' => __('messages.total_materials'), 'value' => $stats['total_materials'], 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'text-violet-600', 'bg' => 'bg-violet-50'],
        ['label' => __('messages.ungrouped'), 'value' => $stats['ungrouped'], 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'text-amber-600', 'bg' => 'bg-amber-50'],
    ] as $s)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl {{ $s['bg'] }} {{ $s['color'] }} flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/></svg>
        </div>
        <div>
            <div class="text-xl font-black {{ $s['color'] }}">{{ $s['value'] }}</div>
            <div class="text-xs text-gray-500">{{ $s['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

@if(session('success'))
<div class="mb-4 flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    {{ session('success') }}
</div>
@endif

{{-- Ungrouped materials alert --}}
@if($ungroupedCount > 0)
<div class="mb-5 flex items-center justify-between gap-3 bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3 rounded-xl">
    <div class="flex items-center gap-2">
        <svg class="w-4 h-4 shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <span><strong>{{ $ungroupedCount }}</strong> {{ __('messages.ungrouped_materials_hint') }}</span>
    </div>
    <a href="{{ route('admin.teacher-materials.index') }}" class="text-xs font-semibold text-amber-700 hover:underline whitespace-nowrap">{{ __('messages.view_all_materials') }} →</a>
</div>
@endif

{{-- Collections Grid --}}
@if($collections->isEmpty())
<div class="bg-white rounded-2xl border border-dashed border-gray-200 p-16 text-center">
    <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
    </div>
    <h3 class="text-lg font-bold text-gray-700 mb-1">{{ __('messages.no_collections_yet') }}</h3>
    <p class="text-sm text-gray-400 mb-5">{{ __('messages.no_collections_hint') }}</p>
    <a href="{{ route('admin.teacher-collections.create') }}"
       class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        {{ __('messages.new_collection') }}
    </a>
</div>
@else

{{-- Drag-to-reorder wrapper --}}
<div id="collections-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($collections as $col)
    <div class="collection-card group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden cursor-default"
         data-id="{{ $col->id }}">

        {{-- Color bar / header --}}
        <div class="h-2 w-full" style="background-color: {{ $col->cover_color }}"></div>

        <div class="p-5">
            {{-- Top row: icon + title + actions --}}
            <div class="flex items-start justify-between gap-2 mb-3">
                <div class="flex items-center gap-3 min-w-0">
                    {{-- Drag handle --}}
                    <div class="drag-handle text-gray-300 hover:text-gray-500 cursor-grab active:cursor-grabbing shrink-0 mt-0.5" title="{{ __('messages.drag_to_reorder') }}">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 6a2 2 0 100-4 2 2 0 000 4zm0 6a2 2 0 100-4 2 2 0 000 4zm0 6a2 2 0 100-4 2 2 0 000 4zm8-12a2 2 0 100-4 2 2 0 000 4zm0 6a2 2 0 100-4 2 2 0 000 4zm0 6a2 2 0 100-4 2 2 0 000 4z"/></svg>
                    </div>
                    {{-- Icon + name --}}
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl shrink-0"
                         style="background-color: {{ $col->cover_color }}20; border: 1px solid {{ $col->cover_color }}30;">
                        {{ $col->icon ?? '📁' }}
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-gray-800 truncate leading-tight">{{ $col->name_localized }}</h3>
                        @if($col->is_private)
                        <span class="inline-flex items-center gap-1 text-xs text-gray-400">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            {{ __('messages.private') }}
                        </span>
                        @endif
                    </div>
                </div>
                {{-- Actions dropdown --}}
                <div class="relative shrink-0" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                            class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 5a2 2 0 110-4 2 2 0 010 4zm0 5a2 2 0 110-4 2 2 0 010 4z"/></svg>
                    </button>
                    <div x-show="open" x-transition
                         class="absolute end-0 top-8 z-20 bg-white rounded-xl shadow-lg border border-gray-100 py-1 w-44 text-sm">
                        <a href="{{ route('admin.teacher-collections.show', $col) }}"
                           class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            {{ __('messages.open') }}
                        </a>
                        <a href="{{ route('admin.teacher-collections.edit', $col) }}"
                           class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586"/></svg>
                            {{ __('messages.edit') }}
                        </a>
                        <a href="{{ route('admin.teacher-collections.create', ['parent_id' => $col->id]) }}"
                           class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            {{ __('messages.add_sub_collection') }}
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('admin.teacher-collections.destroy', $col) }}"
                              onsubmit="return confirm('{{ __('messages.confirm_delete_collection') }}')">
                            @csrf @method('DELETE')
                            <button class="w-full flex items-center gap-2 px-4 py-2 text-red-500 hover:bg-red-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                {{ __('messages.delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            @if($col->description)
            <p class="text-xs text-gray-500 mb-3 line-clamp-2 leading-relaxed">{{ $col->description }}</p>
            @endif

            {{-- Material count + sub-collection chips --}}
            <div class="flex items-center gap-2 flex-wrap mb-3">
                <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full"
                      style="background-color: {{ $col->cover_color }}18; color: {{ $col->cover_color }};">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    {{ $col->materials->count() }} {{ __('messages.materials') }}
                </span>
                @if($col->children->count() > 0)
                <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 text-gray-500">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                    {{ $col->children->count() }} {{ __('messages.sub_collections') }}
                </span>
                @endif
            </div>

            {{-- Sub-collection chips --}}
            @if($col->children->count() > 0)
            <div class="flex flex-wrap gap-1.5 mb-3">
                @foreach($col->children->take(4) as $sub)
                <a href="{{ route('admin.teacher-collections.show', $sub) }}"
                   class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-lg border bg-gray-50 hover:bg-gray-100 text-gray-600 transition truncate max-w-[140px]">
                    {{ $sub->icon ?? '📂' }} {{ $sub->name_localized }}
                    <span class="text-gray-400 shrink-0">({{ $sub->materials->count() }})</span>
                </a>
                @endforeach
                @if($col->children->count() > 4)
                <span class="text-xs text-gray-400 px-2 py-1">+{{ $col->children->count() - 4 }} more</span>
                @endif
            </div>
            @endif

            {{-- View button --}}
            <a href="{{ route('admin.teacher-collections.show', $col) }}"
               class="block w-full text-center text-xs font-semibold py-2 rounded-xl border transition"
               style="border-color: {{ $col->cover_color }}40; color: {{ $col->cover_color }}; background-color: {{ $col->cover_color }}08;"
               onmouseover="this.style.backgroundColor='{{ $col->cover_color }}18'"
               onmouseout="this.style.backgroundColor='{{ $col->cover_color }}08'">
                {{ __('messages.open_collection') }} →
            </a>
        </div>
    </div>
    @endforeach
</div>

@endif

@endsection

@push('scripts')
<script>
// Sortable drag-to-reorder (vanilla, no library needed for a grid)
(function() {
    const grid = document.getElementById('collections-grid');
    if (!grid) return;

    let dragging = null;

    grid.querySelectorAll('.drag-handle').forEach(handle => {
        handle.addEventListener('mousedown', (e) => {
            const card = handle.closest('.collection-card');
            card.setAttribute('draggable', true);
        });
    });

    grid.addEventListener('dragstart', e => {
        dragging = e.target.closest('.collection-card');
        setTimeout(() => dragging && dragging.classList.add('opacity-40', 'scale-95'), 0);
    });

    grid.addEventListener('dragend', e => {
        const card = e.target.closest('.collection-card');
        if (card) {
            card.classList.remove('opacity-40', 'scale-95');
            card.setAttribute('draggable', false);
        }
        dragging = null;
        saveOrder();
    });

    grid.addEventListener('dragover', e => {
        e.preventDefault();
        const target = e.target.closest('.collection-card');
        if (target && target !== dragging) {
            const rect = target.getBoundingClientRect();
            const mid  = rect.left + rect.width / 2;
            if (e.clientX < mid) {
                grid.insertBefore(dragging, target);
            } else {
                grid.insertBefore(dragging, target.nextSibling);
            }
        }
    });

    function saveOrder() {
        const order = [...grid.querySelectorAll('.collection-card')].map(c => c.dataset.id);
        fetch('{{ route('admin.teacher-collections.reorder') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ order }),
        });
    }
})();
</script>
@endpush
