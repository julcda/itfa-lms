@extends('layouts.admin')
@section('title', __('messages.enrollments'))
@section('page-title', __('messages.enrollments'))

@section('content')

{{-- Filters --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 p-5">
    <form class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs text-gray-500 mb-1">{{ __('messages.search') }}</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_student') }}"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-300 w-52">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">{{ __('messages.course') }}</label>
            <select name="course_id" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-300">
                <option value="">{{ __('messages.all_courses') }}</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ request('course_id')==$course->id ? 'selected' : '' }}>{{ $course->title_localized }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">{{ __('messages.status') }}</label>
            <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-300">
                <option value="">{{ __('messages.all') }}</option>
                @foreach(['active','completed','dropped'] as $s)
                    <option value="{{ $s }}" {{ request('status')===$s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <button class="bg-violet-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-violet-700 transition">{{ __('messages.filter') }}</button>
        <a href="{{ route('admin.enrollments.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition">+ {{ __('messages.enroll_students') }}</a>
        @if(request()->hasAny(['search','course_id','status']))
            <a href="{{ route('admin.enrollments.index') }}" class="border border-gray-200 text-gray-500 px-3 py-2 rounded-lg text-sm hover:bg-gray-50 transition">{{ __('messages.clear') }}</a>
        @endif
    </form>
</div>

@if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>
@endif

{{-- Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-violet-50 text-violet-700 text-xs uppercase">
                <tr>
                    <th class="px-5 py-3 text-start">{{ __('messages.student') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.course') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.grade_level') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.enrolled_at') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.status') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.enrolled_by') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($enrollments as $enrollment)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-violet-100 text-violet-700 rounded-full flex items-center justify-center text-xs font-bold">{{ strtoupper(substr($enrollment->user->name ?? 'U', 0, 1)) }}</div>
                            <div>
                                <div class="font-medium text-gray-800">{{ $enrollment->user->name ?? '-' }}</div>
                                <div class="text-xs text-gray-400">
                                    {{ $enrollment->user->email ?? '' }}
                                    @if($enrollment->user->lrn) · LRN: {{ $enrollment->user->lrn }}@endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <a href="{{ route('admin.courses.show', $enrollment->course_id) }}" class="text-emerald-600 hover:text-emerald-800 font-medium">{{ $enrollment->course->title_localized ?? '-' }}</a>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">
                        {{ $enrollment->user->grade_level ? (\App\Models\Course::GRADE_LEVELS[$enrollment->user->grade_level] ?? $enrollment->user->grade_level) : '-' }}
                        @if($enrollment->user->section)<div class="text-gray-400">{{ $enrollment->user->section }}</div>@endif
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $enrollment->enrolled_at?->format('d M Y') ?? $enrollment->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3">
                        <form method="POST" action="{{ route('admin.enrollments.update', $enrollment) }}">
                            @csrf @method('PUT')
                            @php $sc = ['active'=>'green','completed'=>'blue','dropped'=>'red']; @endphp
                            <select name="status" onchange="this.form.submit()"
                                class="text-xs border border-gray-200 rounded-lg px-2 py-1 text-{{ $sc[$enrollment->status] ?? 'gray' }}-600 bg-{{ $sc[$enrollment->status] ?? 'gray' }}-50 focus:outline-none cursor-pointer">
                                @foreach(['active','completed','dropped'] as $s)
                                    <option value="{{ $s }}" {{ $enrollment->status===$s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $enrollment->enrolledBy->name ?? __('messages.self_enrolled') }}</td>
                    <td class="px-5 py-3">
                        <form method="POST" action="{{ route('admin.enrollments.destroy', $enrollment) }}" onsubmit="return confirm('{{ __('messages.confirm_unenroll') }}')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-400 hover:text-red-600 font-medium">{{ __('messages.unenroll') }}</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">{{ __('messages.no_enrollments_yet') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($enrollments->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $enrollments->links() }}</div>
    @endif
</div>
@endsection
