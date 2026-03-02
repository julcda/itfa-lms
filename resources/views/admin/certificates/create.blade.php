@extends('layouts.admin')
@section('title', __('messages.generate_certificate'))
@section('page-title', __('messages.generate_certificate'))

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.certificates.generate') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.student') }} *</label>
                <select name="user_id" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    <option value="">{{ __('messages.select_student') }}</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('user_id')==$student->id ? 'selected' : '' }}>{{ $student->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.course') }} *</label>
                <select name="course_id" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    <option value="">{{ __('messages.select_course') }}</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id')==$course->id ? 'selected' : '' }}>{{ $course->title_localized }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.issued_at') }}</label>
                <input type="date" name="issued_at" value="{{ old('issued_at', date('Y-m-d')) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
            </div>
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 text-sm text-amber-800">
                {{ __('messages.certificate_generate_info') }}
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition">{{ __('messages.generate_and_save') }}</button>
                <a href="{{ route('admin.certificates.index') }}" class="border border-gray-200 text-gray-600 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">{{ __('messages.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
