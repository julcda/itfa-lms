@extends('layouts.admin')
@section('title', __('messages.attendance_by_course'))
@section('page-title', __('messages.attendance_by_course'))

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @forelse($courses as $course)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-800 mb-3">{{ $course->title_localized }}</h3>
        <div class="grid grid-cols-2 gap-3 text-center mb-4">
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-lg font-bold text-gray-800">{{ $course->attendances_count ?? 0 }}</div>
                <div class="text-xs text-gray-400">{{ __('messages.total_records') }}</div>
            </div>
            <div class="bg-green-50 rounded-lg p-3">
                <div class="text-lg font-bold text-green-700">{{ $course->present_count ?? 0 }}</div>
                <div class="text-xs text-gray-400">{{ __('messages.present') }}</div>
            </div>
        </div>
        <a href="{{ route('admin.attendance.index', ['course_id' => $course->id]) }}" class="block text-center w-full border border-emerald-200 text-emerald-700 py-2 rounded-lg text-sm hover:bg-emerald-50 transition">
            {{ __('messages.view_attendance') }}
        </a>
    </div>
    @empty
    <div class="col-span-3 text-center py-10 text-gray-400">{{ __('messages.no_courses_yet') }}</div>
    @endforelse
</div>
@endsection
