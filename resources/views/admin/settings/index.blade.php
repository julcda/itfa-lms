@extends('layouts.admin')
@section('title', __('messages.settings'))
@section('page-title', __('messages.settings'))

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3" style="background: linear-gradient(90deg,#f0fdf4,#ecfdf5)">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#10b981">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-gray-800 text-sm">{{ __('messages.school_branding') }}</h2>
                <p class="text-xs text-gray-400">{{ __('messages.school_branding_desc') }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            {{-- School Name --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('messages.school_name_en') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="school_name"
                           value="{{ old('school_name', $settings['school_name'] ?? '') }}"
                           required
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('school_name') border-red-400 @enderror">
                    @error('school_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.school_name_ar') }}</label>
                    <input type="text" name="school_name_ar"
                           value="{{ old('school_name_ar', $settings['school_name_ar'] ?? '') }}"
                           dir="rtl"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
            </div>

            {{-- Short Name --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('messages.school_short_name') }} <span class="text-red-500">*</span>
                        <span class="text-xs text-gray-400 font-normal ms-1">({{ __('messages.short_name_hint') }})</span>
                    </label>
                    <input type="text" name="school_short_name"
                           value="{{ old('school_short_name', $settings['school_short_name'] ?? '') }}"
                           required maxlength="20"
                           placeholder="e.g. ITFA"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('school_short_name') border-red-400 @enderror">
                    @error('school_short_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Tagline --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.school_tagline_en') }}</label>
                    <input type="text" name="school_tagline"
                           value="{{ old('school_tagline', $settings['school_tagline'] ?? '') }}"
                           placeholder="e.g. Learning Management System"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.school_tagline_ar') }}</label>
                    <input type="text" name="school_tagline_ar"
                           value="{{ old('school_tagline_ar', $settings['school_tagline_ar'] ?? '') }}"
                           dir="rtl"
                           placeholder="مثال: نظام إدارة التعلم"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
            </div>

            {{-- Logo --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.school_logo') }}</label>
                @php $currentLogo = $settings['school_logo'] ?? null; @endphp
                @if($currentLogo)
                <div class="flex items-center gap-4 mb-3 p-3 bg-gray-50 rounded-lg border border-gray-100">
                    <img src="{{ Storage::disk('public')->url($currentLogo) }}"
                         alt="School Logo"
                         class="h-14 max-w-[120px] object-contain rounded">
                    <div class="flex-1">
                        <p class="text-xs text-gray-500">{{ __('messages.current_logo') }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ basename($currentLogo) }}</p>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remove_logo" value="1" class="rounded border-gray-300 text-red-500 focus:ring-red-400">
                        <span class="text-xs text-red-500 font-medium">{{ __('messages.remove_logo') }}</span>
                    </label>
                </div>
                @endif
                <input type="file" name="school_logo" accept="image/*"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                <p class="text-xs text-gray-400 mt-1">{{ __('messages.logo_hint') }}</p>
                @error('school_logo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Preview --}}
            <div class="border border-dashed border-gray-200 rounded-xl p-4 bg-gray-50">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">{{ __('messages.preview') }}</p>
                <div class="flex items-center gap-3">
                    @if($currentLogo)
                        <img src="{{ Storage::disk('public')->url($currentLogo) }}" class="h-10 max-w-[80px] object-contain rounded-lg" id="preview-img">
                    @else
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-black text-xs shrink-0"
                             style="background: linear-gradient(135deg,#34d399,#059669)"
                             id="preview-abbr">{{ strtoupper(substr($settings['school_short_name'] ?? 'S', 0, 2)) }}</div>
                    @endif
                    <div>
                        <div class="font-bold text-gray-800 text-sm" id="preview-name">{{ $settings['school_name'] ?? '' }}</div>
                        <div class="text-xs text-gray-400" id="preview-tagline">{{ $settings['school_tagline'] ?? '' }}</div>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-2 border-t border-gray-100">
                <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition">
                    {{ __('messages.save_settings') }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Live preview
    document.querySelector('[name=school_name]')?.addEventListener('input', function() {
        document.getElementById('preview-name').textContent = this.value;
    });
    document.querySelector('[name=school_tagline]')?.addEventListener('input', function() {
        document.getElementById('preview-tagline').textContent = this.value;
    });
    document.querySelector('[name=school_short_name]')?.addEventListener('input', function() {
        const el = document.getElementById('preview-abbr');
        if (el) el.textContent = this.value.toUpperCase().slice(0, 2);
    });
    document.querySelector('[name=school_logo]')?.addEventListener('change', function() {
        if (this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                const existing = document.getElementById('preview-img');
                const abbr = document.getElementById('preview-abbr');
                if (existing) {
                    existing.src = e.target.result;
                } else if (abbr) {
                    const img = document.createElement('img');
                    img.id = 'preview-img';
                    img.className = 'h-10 max-w-[80px] object-contain rounded-lg';
                    img.src = e.target.result;
                    abbr.replaceWith(img);
                }
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endpush
@endsection
