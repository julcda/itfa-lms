@extends('layouts.admin')
@section('title', __('messages.add_user'))
@section('page-title', __('messages.add_user'))

@php
    $gradeLevels = \App\Models\Course::GRADE_LEVELS;
    $strands     = \App\Models\Course::STRANDS;
@endphp

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.name') }} *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('name') border-red-400 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.arabic_name') }}</label>
                    <input type="text" name="arabic_name" value="{{ old('arabic_name') }}" dir="rtl" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.email') }} *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('email') border-red-400 @enderror">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.password') }} *</label>
                    <input type="password" name="password" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('password') border-red-400 @enderror">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.confirm_password') }} *</label>
                    <input type="password" name="password_confirmation" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.role') }} *</label>
                    <select name="role" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        <option value="">{{ __('messages.select_role') }}</option>
                        @foreach(['admin','teacher','student'] as $r)
                            <option value="{{ $r }}" {{ old('role')===$r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.gender') }}</label>
                    <select name="gender" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        <option value="">{{ __('messages.select') }}</option>
                        <option value="male" {{ old('gender')==='male' ? 'selected' : '' }}>{{ __('messages.male') }}</option>
                        <option value="female" {{ old('gender')==='female' ? 'selected' : '' }}>{{ __('messages.female') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.date_of_birth') }}</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.language') }}</label>
                    <select name="locale" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        <option value="en" {{ old('locale','en')==='en' ? 'selected' : '' }}>English</option>
                        <option value="ar" {{ old('locale')==='ar' ? 'selected' : '' }}>العربية</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.bio') }}</label>
                <textarea name="bio" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">{{ old('bio') }}</textarea>
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                <label for="is_active" class="text-sm text-gray-700">{{ __('messages.active') }}</label>
            </div>

            {{-- K-12 DepEd Section (Student Fields) --}}
            <div class="border-t border-gray-100 pt-5">
                <h3 class="text-sm font-semibold text-blue-700 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    {{ __('messages.deped_k12_info') }} <span class="text-gray-400 font-normal text-xs">({{ __('messages.for_students') }})</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.lrn') }}</label>
                        <input type="text" name="lrn" value="{{ old('lrn') }}" maxlength="12" placeholder="12-digit Learner Reference No." class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('lrn') border-red-400 @enderror">
                        @error('lrn')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.school_year') }}</label>
                        <input type="text" name="school_year" value="{{ old('school_year', '2025-2026') }}" placeholder="2025-2026" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    </div>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.section') }}</label>
                        <input type="text" name="section" value="{{ old('section') }}" placeholder="e.g. Einstein, Rizal" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.shs_strand') }} <span class="text-gray-400 text-xs">(SHS only)</span></label>
                        <select name="strand" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="">{{ __('messages.not_applicable') }}</option>
                            @foreach($strands as $val => $label)
                                <option value="{{ $val }}" {{ old('strand')===$val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition">{{ __('messages.save') }}</button>
                <a href="{{ route('admin.users.index') }}" class="border border-gray-200 text-gray-600 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">{{ __('messages.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
