@extends('layouts.admin')
@section('title', __('messages.edit_course'))
@section('page-title', __('messages.edit_course').': '.$course->title_localized)

@php
    $gradeLevels  = \App\Models\Course::GRADE_LEVELS;
    $learningAreas = \App\Models\Course::LEARNING_AREAS;
    $strands      = \App\Models\Course::STRANDS;
    $quarters     = \App\Models\Course::QUARTERS;
@endphp

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.courses.update', $course) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.title_en') }} *</label>
                    <input type="text" name="title" value="{{ old('title', $course->title) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.title_ar') }}</label>
                    <input type="text" name="title_ar" value="{{ old('title_ar', $course->title_ar) }}" dir="rtl" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.description') }}</label>
                    <textarea name="description" rows="4" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">{{ old('description', $course->description) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.description_ar') }}</label>
                    <textarea name="description_ar" rows="4" dir="rtl" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">{{ old('description_ar', $course->description_ar) }}</textarea>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.teacher') }}</label>
                    <select name="teacher_id" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $course->teacher_id)==$teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.category') }}</label>
                    <select name="category_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        <option value="">-</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $course->category_id)==$cat->id ? 'selected' : '' }}>{{ $cat->name_localized }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.level') }}</label>
                    <select name="level" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        @foreach(['beginner','intermediate','advanced'] as $lv)
                            <option value="{{ $lv }}" {{ old('level',$course->level)===$lv ? 'selected' : '' }}>{{ __('messages.level_'.$lv) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.status') }}</label>
                    <select name="status" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        @foreach(['draft','published','archived'] as $s)
                            <option value="{{ $s }}" {{ old('status',$course->status)===$s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.thumbnail') }}</label>
                    @if($course->thumbnail)
                        <div class="mb-2"><img src="{{ $course->thumbnail_url }}" class="h-16 w-24 object-cover rounded"></div>
                    @endif
                    <input type="file" name="thumbnail" accept="image/*" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $course->is_featured) ? 'checked' : '' }} class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                <label for="is_featured" class="text-sm text-gray-700">{{ __('messages.featured') }}</label>
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
                                <option value="{{ $val }}" {{ old('grade_level', $course->grade_level)===$val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.learning_area') }}</label>
                        <select name="learning_area" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="">{{ __('messages.select') }}</option>
                            @foreach($learningAreas as $val => $label)
                                <option value="{{ $val }}" {{ old('learning_area', $course->learning_area)===$val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.quarter') }}</label>
                        <select name="quarter" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="">{{ __('messages.select') }}</option>
                            @foreach($quarters as $val => $label)
                                <option value="{{ $val }}" {{ old('quarter', $course->quarter)===$val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.school_year') }}</label>
                        <input type="text" name="school_year" value="{{ old('school_year', $course->school_year) }}" placeholder="2025-2026" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.shs_strand') }} <span class="text-gray-400 text-xs">(SHS only)</span></label>
                        <select name="strand" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="">{{ __('messages.not_applicable') }}</option>
                            @foreach($strands as $val => $label)
                                <option value="{{ $val }}" {{ old('strand', $course->strand)===$val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.subject_code') }} <span class="text-gray-400 text-xs">(DepEd Code)</span></label>
                        <input type="text" name="subject_code" value="{{ old('subject_code', $course->subject_code) }}" placeholder="e.g. ENG7-Q1" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    </div>
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition">{{ __('messages.update') }}</button>
                <a href="{{ route('admin.courses.show', $course) }}" class="border border-gray-200 text-gray-600 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">{{ __('messages.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
