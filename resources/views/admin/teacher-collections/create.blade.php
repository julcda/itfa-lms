@extends('layouts.admin')
@section('title', __('messages.new_collection'))
@section('page-title', __('messages.teacher_collections'))

@section('content')
<div class="max-w-2xl">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-sm text-gray-500 mb-5">
        <a href="{{ route('admin.teacher-collections.index') }}" class="hover:text-emerald-600">{{ __('messages.teacher_collections') }}</a>
        <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        @if($parent)
        <a href="{{ route('admin.teacher-collections.show', $parent) }}" class="hover:text-emerald-600">{{ $parent->name_localized }}</a>
        <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        @endif
        <span class="text-gray-700 font-semibold">{{ __('messages.new_collection') }}</span>
    </nav>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-bold text-gray-800">{{ __('messages.new_collection') }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ __('messages.collection_form_hint') }}</p>
        </div>

        <form method="POST" action="{{ route('admin.teacher-collections.store') }}" class="p-6 space-y-5" x-data="collectionForm()">
            @csrf

            @if($parent)
            <input type="hidden" name="parent_id" value="{{ $parent->id }}">
            @endif

            {{-- Live preview --}}
            <div class="p-4 rounded-2xl border-2 flex items-center gap-4 transition-all"
                 :style="'border-color:'+color+'40; background-color:'+color+'08'">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl shrink-0 transition-all"
                     :style="'background-color:'+color+'20; border: 2px solid '+color+'30'">
                    <span x-text="icon || '📁'"></span>
                </div>
                <div>
                    <div class="font-bold text-gray-800 text-sm" x-text="name || '{{ __('messages.collection_name_placeholder') }}'"></div>
                    <div class="text-xs text-gray-400 mt-0.5" x-text="description || '{{ __('messages.collection_desc_placeholder') }}'"></div>
                </div>
            </div>

            {{-- Parent (only shown if no parent pre-set) --}}
            @if(!$parent)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.parent_collection') }}</label>
                <select name="parent_id" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    <option value="">— {{ __('messages.root_collection') }} —</option>
                    @foreach($parents as $p)
                    <option value="{{ $p->id }}" {{ old('parent_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->icon ?? '📁' }} {{ $p->name_localized }}
                    </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">{{ __('messages.parent_collection_hint') }}</p>
            </div>
            @endif

            {{-- Name --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.name_en') }} *</label>
                    <input type="text" name="name" x-model="name" value="{{ old('name') }}" required
                           placeholder="{{ __('messages.collection_name_placeholder') }}"
                           class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('name') border-red-400 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.name_ar') }}</label>
                    <input type="text" name="name_ar" value="{{ old('name_ar') }}" dir="rtl"
                           placeholder="اسم المجموعة"
                           class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.description') }}</label>
                <textarea name="description" x-model="description" rows="2"
                          placeholder="{{ __('messages.collection_desc_placeholder') }}"
                          class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 resize-none">{{ old('description') }}</textarea>
            </div>

            {{-- Icon picker --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.icon') }}</label>
                <div class="flex flex-wrap gap-2">
                    @foreach(\App\Models\TeacherCollection::iconOptions() as $ic)
                    <button type="button" @click="icon = '{{ $ic }}'"
                            :class="icon === '{{ $ic }}' ? 'ring-2 ring-emerald-500 bg-emerald-50' : 'bg-gray-50 hover:bg-gray-100'"
                            class="w-10 h-10 rounded-xl text-xl flex items-center justify-center border border-gray-200 transition text-base">
                        {{ $ic }}
                    </button>
                    @endforeach
                </div>
                <input type="hidden" name="icon" :value="icon">
            </div>

            {{-- Color picker --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.cover_color') }}</label>
                <div class="flex flex-wrap gap-2.5">
                    @foreach(\App\Models\TeacherCollection::colorPalette() as $hex => $label)
                    <button type="button" @click="color = '{{ $hex }}'"
                            :class="color === '{{ $hex }}' ? 'ring-2 ring-offset-2 ring-gray-400 scale-110' : 'hover:scale-105'"
                            class="w-8 h-8 rounded-full transition-all shadow-sm border-2 border-white"
                            style="background-color: {{ $hex }}"
                            title="{{ $label }}">
                    </button>
                    @endforeach
                </div>
                <input type="hidden" name="cover_color" :value="color">
            </div>

            {{-- Visibility --}}
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                <input type="checkbox" name="is_private" value="1" id="is_private"
                       {{ old('is_private') ? 'checked' : '' }}
                       class="w-4 h-4 rounded text-emerald-600 border-gray-300 focus:ring-emerald-400">
                <div>
                    <label for="is_private" class="text-sm font-medium text-gray-700 cursor-pointer">{{ __('messages.private_collection') }}</label>
                    <p class="text-xs text-gray-400">{{ __('messages.private_collection_hint') }}</p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm px-6 py-2.5 rounded-xl shadow transition active:scale-95">
                    {{ __('messages.create_collection') }}
                </button>
                <a href="{{ $parent ? route('admin.teacher-collections.show', $parent) : route('admin.teacher-collections.index') }}"
                   class="border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 text-sm px-5 py-2.5 rounded-xl transition">
                    {{ __('messages.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function collectionForm() {
    return {
        name: '{{ old('name') }}',
        description: '{{ old('description') }}',
        icon: '{{ old('icon', '📁') }}',
        color: '{{ old('cover_color', '#10b981') }}',
    };
}
</script>
@endpush
