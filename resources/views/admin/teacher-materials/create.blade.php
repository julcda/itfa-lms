@extends('layouts.admin')
@section('title', __('messages.add_material'))
@section('page-title', __('messages.add_material'))

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.teacher-materials.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- Title --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.title_en') }} *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('title') border-red-400 @enderror">
                    @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.title_ar') }}</label>
                    <input type="text" name="title_ar" value="{{ old('title_ar') }}" dir="rtl"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
            </div>

            {{-- Description --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.description_en') }}</label>
                    <textarea name="description" rows="3"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.description_ar') }}</label>
                    <textarea name="description_ar" rows="3" dir="rtl"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">{{ old('description_ar') }}</textarea>
                </div>
            </div>

            {{-- Classification --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.material_type') }} *</label>
                    <select name="material_type" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        @foreach(\App\Models\TeacherMaterial::allTypes() as $t)
                            <option value="{{ $t }}" {{ old('material_type', 'pdf') === $t ? 'selected' : '' }}>{{ strtoupper($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.language') }}</label>
                    <select name="language"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        <option value="english"  {{ old('language','english')==='english'  ? 'selected':'' }}>English</option>
                        <option value="arabic"   {{ old('language')==='arabic'   ? 'selected':'' }}>Arabic / عربي</option>
                        <option value="bilingual"{{ old('language')==='bilingual' ? 'selected':'' }}>Bilingual</option>
                        <option value="filipino" {{ old('language')==='filipino'  ? 'selected':'' }}>Filipino</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.status') }} *</label>
                    <select name="status" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        <option value="active" {{ old('status','active')==='active' ? 'selected':'' }}>{{ __('messages.active') }}</option>
                        <option value="draft"  {{ old('status')==='draft' ? 'selected':'' }}>{{ __('messages.draft') }}</option>
                    </select>
                </div>
            </div>

            {{-- Subject / Grade / Category --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.subject') }}</label>
                    <input type="text" name="subject" value="{{ old('subject') }}"
                           placeholder="e.g. Mathematics, Science"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.grade_level') }}</label>
                    <input type="text" name="grade_level" value="{{ old('grade_level') }}"
                           placeholder="e.g. Grade 7, All Grades"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.category') }}</label>
                    <select name="category_id"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        <option value="">—</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id')==$cat->id ? 'selected':'' }}>{{ $cat->name_localized }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Source / Year --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.source') }}</label>
                    <input type="text" name="source" value="{{ old('source') }}"
                           placeholder="{{ __('messages.source_placeholder') }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.published_year') }}</label>
                    <input type="number" name="published_year" value="{{ old('published_year') }}"
                           min="1900" max="{{ date('Y') + 1 }}" placeholder="{{ date('Y') }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
            </div>

            {{-- Tags --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.tags') }}</label>
                <input type="text" name="tags"
                       value="{{ old('tags', is_array(old('tags')) ? implode(', ', old('tags')) : old('tags')) }}"
                       placeholder="{{ __('messages.tags_placeholder') }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                <p class="text-xs text-gray-400 mt-1">{{ __('messages.comma_separated') }}</p>
            </div>

            {{-- External URL --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.external_url') }}</label>
                <input type="url" name="external_url" value="{{ old('external_url') }}"
                       placeholder="https://…"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                <p class="text-xs text-gray-400 mt-1">{{ __('messages.leave_blank_for_upload') }}</p>
            </div>

            {{-- File / Cover --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.file') }}</label>
                    <input type="file" name="file_path"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    <p class="text-xs text-gray-400 mt-1">{{ __('messages.max_100mb') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.cover_image') }}</label>
                    <input type="file" name="cover_image" accept="image/*"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    <p class="text-xs text-gray-400 mt-1">{{ __('messages.max_2mb') }}</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition">
                    {{ __('messages.save') }}
                </button>
                <a href="{{ route('admin.teacher-materials.index') }}"
                   class="border border-gray-200 text-gray-600 hover:bg-gray-50 px-5 py-2.5 rounded-lg text-sm transition">
                    {{ __('messages.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
