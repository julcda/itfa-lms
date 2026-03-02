@extends('layouts.admin')
@section('title', __('messages.courses'))
@section('page-title', __('messages.courses'))

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <form class="flex flex-wrap gap-2 flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search') }}..." class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 w-48">
            <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                <option value="">{{ __('messages.all_statuses') }}</option>
                @foreach(['draft','published','archived'] as $s)
                    <option value="{{ $s }}" {{ request('status')===$s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-emerald-700 transition">{{ __('messages.search') }}</button>
        </form>
        <a href="{{ route('admin.courses.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition shrink-0">+ {{ __('messages.add_course') }}</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-5 py-3 text-start">{{ __('messages.course') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.teacher') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.status') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.level') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.enrollments') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($courses as $course)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-lg overflow-hidden shrink-0">
                                @if($course->thumbnail)
                                    <img src="{{ $course->thumbnail_url }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-emerald-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">{{ $course->title_localized }}</div>
                                <div class="text-xs text-gray-400">{{ $course->lessons_count ?? $course->lessons->count() }} {{ __('messages.lessons') }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $course->teacher->name ?? '-' }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs {{ $course->status === 'published' ? 'bg-green-100 text-green-700' : ($course->status === 'draft' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500') }}">{{ ucfirst($course->status) }}</span>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ __('messages.level_'.$course->level) }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $course->enrollments_count ?? $course->enrollments->count() }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.courses.show', $course) }}" class="text-gray-400 hover:text-emerald-600 transition" title="{{ __('messages.view') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('admin.courses.edit', $course) }}" class="text-gray-400 hover:text-blue-600 transition" title="{{ __('messages.edit') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">{{ __('messages.no_data_yet') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">{{ $courses->withQueryString()->links() }}</div>
</div>
@endsection
