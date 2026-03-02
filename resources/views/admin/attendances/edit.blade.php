@extends('layouts.admin')
@section('title', __('messages.edit_attendance'))
@section('page-title', __('messages.edit_attendance'))

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.attendance.update', $attendance) }}" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.student') }}</label>
                <p class="text-sm text-gray-800 font-medium">{{ $attendance->user->name ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.course') }}</label>
                <p class="text-sm text-gray-800">{{ $attendance->course->title_localized ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.session_date') }}</label>
                <input type="date" name="session_date" value="{{ old('session_date', $attendance->session_date) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.status') }}</label>
                <select name="status" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    @foreach(['present','absent','late','excused'] as $s)
                        <option value="{{ $s }}" {{ old('status',$attendance->status)===$s ? 'selected' : '' }} class="capitalize">{{ __('messages.'.$s) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.notes') }}</label>
                <textarea name="notes" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">{{ old('notes', $attendance->notes) }}</textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition">{{ __('messages.update') }}</button>
                <a href="{{ route('admin.attendance.index') }}" class="border border-gray-200 text-gray-600 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">{{ __('messages.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
