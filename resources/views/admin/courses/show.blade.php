@extends('layouts.admin')
@section('title', $course->title_localized)
@section('page-title', $course->title_localized)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-4">
        {{-- Course card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            @if($course->thumbnail)
                <img src="{{ $course->thumbnail_url }}" class="w-full h-52 object-cover">
            @else
                <div class="w-full h-52 bg-gradient-to-br from-emerald-100 to-emerald-200 flex items-center justify-center">
                    <svg class="w-16 h-16 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
            @endif
            <div class="p-5">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">{{ $course->title }}</h2>
                        @if($course->title_ar)<p class="text-gray-500" dir="rtl">{{ $course->title_ar }}</p>@endif
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm {{ $course->status === 'published' ? 'bg-green-100 text-green-700' : ($course->status === 'draft' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500') }}">{{ ucfirst($course->status) }}</span>
                </div>
                @if($course->description)<p class="text-gray-600 text-sm">{{ $course->description }}</p>@endif
                <div class="flex gap-3 mt-4">
                    <a href="{{ route('admin.courses.edit', $course) }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition">{{ __('messages.edit') }}</a>
                    <a href="{{ route('admin.courses.lessons.create', $course) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">+ {{ __('messages.add_lesson') }}</a>
                    <a href="{{ route('admin.enrollments.create', ['course_id' => $course->id]) }}" class="bg-violet-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-violet-700 transition">
                        <svg class="w-4 h-4 inline -mt-0.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        {{ __('messages.enroll_students') }}
                    </a>
                    <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="border border-red-200 text-red-600 px-4 py-2 rounded-lg text-sm hover:bg-red-50 transition">{{ __('messages.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Lessons --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between p-5 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">{{ __('messages.lessons') }} ({{ $course->lessons->count() }})</h3>
                <a href="{{ route('admin.courses.lessons.create', $course) }}" class="text-emerald-600 hover:text-emerald-700 text-sm font-medium">+ {{ __('messages.add') }}</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($course->lessons()->orderBy('order')->get() as $lesson)
                <div class="flex items-center justify-between px-5 py-3 hover:bg-gray-50">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 bg-gray-100 rounded-full flex items-center justify-center text-xs font-bold text-gray-500">{{ $lesson->order }}</span>
                        <div>
                            <a href="{{ route('admin.courses.lessons.show', [$course, $lesson]) }}" class="text-sm font-medium text-gray-800 hover:text-emerald-600 transition">{{ $lesson->title_localized }}</a>
                            <div class="text-xs text-gray-400 flex items-center gap-2">
                                <span>{{ ucfirst($lesson->type ?? 'text') }}</span>
                                @if($lesson->duration_minutes)<span>· {{ $lesson->duration_minutes }}min</span>@endif
                                @if($lesson->is_free)<span class="text-green-600">· Free</span>@endif
                                <span class="px-1.5 py-0.5 rounded {{ $lesson->status === 'published' ? 'bg-green-50 text-green-600' : 'bg-yellow-50 text-yellow-600' }}">{{ ucfirst($lesson->status ?? 'draft') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.courses.lessons.show', [$course, $lesson]) }}" class="text-xs text-gray-400 hover:text-gray-600">{{ __('messages.view') }}</a>
                        <a href="{{ route('admin.courses.lessons.edit', [$course, $lesson]) }}" class="text-xs text-blue-500 hover:text-blue-700">{{ __('messages.edit') }}</a>
                        <form method="POST" action="{{ route('admin.courses.lessons.destroy', [$course, $lesson]) }}" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-400 hover:text-red-600">{{ __('messages.delete') }}</button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="px-5 py-6 text-center text-gray-400 text-sm">{{ __('messages.no_lessons_yet') }}</div>
                @endforelse
            </div>
        </div>

        {{-- Enrollments panel --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between p-5 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">{{ __('messages.enrolled_students') }} ({{ $course->enrollments->count() }})</h3>
                <a href="{{ route('admin.enrollments.create', ['course_id' => $course->id]) }}" class="bg-violet-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-violet-700 transition">+ {{ __('messages.enroll_students') }}</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($course->enrollments()->with('user')->latest()->get() as $enrollment)
                <div class="flex items-center justify-between px-5 py-3 hover:bg-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-violet-100 text-violet-700 rounded-full flex items-center justify-center text-xs font-bold">{{ strtoupper(substr($enrollment->user->name ?? 'U', 0, 1)) }}</div>
                        <div>
                            <div class="text-sm font-medium text-gray-800">{{ $enrollment->user->name ?? '-' }}</div>
                            <div class="text-xs text-gray-400">{{ $enrollment->user->email ?? '' }} &ensp; Enrolled: {{ $enrollment->enrolled_at?->format('d M Y') ?? $enrollment->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        @php $statusColors = ['active'=>'green','completed'=>'blue','dropped'=>'red']; @endphp
                        <form method="POST" action="{{ route('admin.enrollments.update', $enrollment) }}" class="flex items-center gap-1">
                            @csrf @method('PUT')
                            <select name="status" onchange="this.form.submit()" class="text-xs border border-gray-200 rounded px-2 py-1 text-{{ $statusColors[$enrollment->status] ?? 'gray' }}-600 focus:outline-none">
                                @foreach(['active','completed','dropped'] as $s)
                                    <option value="{{ $s }}" {{ $enrollment->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </form>
                        <form method="POST" action="{{ route('admin.enrollments.destroy', $enrollment) }}" onsubmit="return confirm('{{ __('messages.confirm_unenroll') }}')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-400 hover:text-red-600">{{ __('messages.unenroll') }}</button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="px-5 py-6 text-center text-gray-400 text-sm">{{ __('messages.no_students_enrolled') }}</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Sidebar info --}}
    <div class="space-y-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">{{ __('messages.course_info') }}</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-gray-400">{{ __('messages.teacher') }}</dt><dd class="font-medium">{{ $course->teacher->name ?? '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-400">{{ __('messages.category') }}</dt><dd>{{ $course->category->name_localized ?? '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-400">{{ __('messages.level') }}</dt><dd>{{ __('messages.level_'.$course->level) }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-400">{{ __('messages.enrollments') }}</dt><dd class="font-bold text-violet-700">{{ $course->enrollments->count() }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-400">{{ __('messages.lessons') }}</dt><dd class="font-bold text-blue-700">{{ $course->lessons->count() }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-400">{{ __('messages.featured') }}</dt><dd>{{ $course->is_featured ? '✓' : '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-400">{{ __('messages.created_at') }}</dt><dd>{{ $course->created_at->format('d M Y') }}</dd></div>
            </dl>
        </div>
        {{-- Quick Actions --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-800 mb-3">{{ __('messages.quick_actions') }}</h3>
            <div class="space-y-2">
                <a href="{{ route('admin.enrollments.create', ['course_id' => $course->id]) }}" class="flex items-center gap-2 w-full text-left px-3 py-2 rounded-lg text-sm text-violet-700 bg-violet-50 hover:bg-violet-100 transition font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    {{ __('messages.enroll_students') }}
                </a>
                <a href="{{ route('admin.courses.lessons.create', $course) }}" class="flex items-center gap-2 w-full text-left px-3 py-2 rounded-lg text-sm text-blue-700 bg-blue-50 hover:bg-blue-100 transition font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ __('messages.add_lesson') }}
                </a>
                <a href="{{ route('admin.courses.edit', $course) }}" class="flex items-center gap-2 w-full text-left px-3 py-2 rounded-lg text-sm text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    {{ __('messages.edit_course') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
