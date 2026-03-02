@extends('layouts.admin')
@section('title', __('messages.add_book'))
@section('page-title', __('messages.add_book'))

@php
    $gradeLevels  = \App\Models\Course::GRADE_LEVELS;
    $learningAreas = \App\Models\Course::LEARNING_AREAS;
@endphp

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.title_en') }} *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('title') border-red-400 @enderror">
                    @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.title_ar') }}</label>
                    <input type="text" name="title_ar" value="{{ old('title_ar') }}" dir="rtl" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.author') }} *</label>
                    <input type="text" name="author" value="{{ old('author') }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.isbn') }}</label>
                    <input type="text" name="isbn" value="{{ old('isbn') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.description_en') }}</label>
                    <textarea name="description" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.description_ar') }}</label>
                    <textarea name="description_ar" rows="3" dir="rtl" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">{{ old('description_ar') }}</textarea>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.file_type') }} *</label>
                    <select name="file_type" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        @foreach(['pdf','video','audio','other','external'] as $t)
                            <option value="{{ $t }}" {{ old('file_type')===$t ? 'selected' : '' }}>{{ strtoupper($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.language') }}</label>
                    <select name="language" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        <option value="english" {{ old('language','english')==='english' ? 'selected' : '' }}>English</option>
                        <option value="filipino" {{ old('language')==='filipino' ? 'selected' : '' }}>Filipino</option>
                        <option value="bilingual" {{ old('language')==='bilingual' ? 'selected' : '' }}>Bilingual</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.category') }}</label>
                    <select name="category_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        <option value="">-</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id')==$cat->id ? 'selected' : '' }}>{{ $cat->name_localized }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.external_url') }}</label>
                <input type="url" name="external_url" value="{{ old('external_url') }}" placeholder="https://..." class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                <p class="text-xs text-gray-400 mt-1">{{ __('messages.leave_blank_for_upload') }}</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.file') }}</label>
                    <input type="file" name="file_path" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.cover_image') }}</label>
                    <input type="file" name="cover_image" accept="image/*" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.tags') }}</label>
                <input type="text" name="tags" value="{{ old('tags') }}" placeholder="{{ __('messages.tags_placeholder') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                <p class="text-xs text-gray-400 mt-1">{{ __('messages.comma_separated') }}</p>
            </div>

            {{-- K-12 DepEd Section --}}
            <div class="border-t border-gray-100 pt-5">
                <h3 class="text-sm font-semibold text-blue-700 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    {{ __('messages.deped_k12_info') }}
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.grade_level') }}</label>
                        <select name="grade_level" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="">{{ __('messages.select') }}</option>
                            @foreach($gradeLevels as $val => $label)
                                <option value="{{ $val }}" {{ old('grade_level')===$val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.learning_area') }}</label>
                        <select name="learning_area" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="">{{ __('messages.select') }}</option>
                            @foreach($learningAreas as $val => $label)
                                <option value="{{ $val }}" {{ old('learning_area')===$val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.deped_material_code') }}</label>
                        <input type="text" name="deped_code" value="{{ old('deped_code') }}" placeholder="e.g. 978-971-07-XXXX" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.edition') }}</label>
                        <input type="text" name="edition" value="{{ old('edition') }}" placeholder="e.g. 2016 Edition" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_downloadable" id="is_downloadable" value="1" {{ old('is_downloadable', true) ? 'checked' : '' }} class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                <label for="is_downloadable" class="text-sm text-gray-700">{{ __('messages.allow_download') }}</label>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.status') }} *</label>
                <select name="status" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('status') border-red-400 @enderror">
                    <option value="active" {{ old('status','active')==='active' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                    <option value="inactive" {{ old('status')==='inactive' ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
                </select>
                @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition">{{ __('messages.save') }}</button>
                <a href="{{ route('admin.books.index') }}" class="border border-gray-200 text-gray-600 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">{{ __('messages.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
