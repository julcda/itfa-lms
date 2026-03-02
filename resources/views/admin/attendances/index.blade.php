@extends('layouts.admin')
@section('title', __('messages.attendance'))
@section('page-title', __('messages.attendance'))

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 p-5">
    <form class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs text-gray-500 mb-1">{{ __('messages.course') }}</label>
            <select name="course_id" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                <option value="">{{ __('messages.all_courses') }}</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ request('course_id')==$course->id ? 'selected' : '' }}>{{ $course->title_localized }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">{{ __('messages.date') }}</label>
            <input type="date" name="date" value="{{ request('date') }}" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
        </div>
        <button class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-emerald-700 transition">{{ __('messages.filter') }}</button>
        <a href="{{ route('admin.attendance.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">{{ __('messages.mark_attendance') }}</a>
        @if($courses->isNotEmpty())
        <a href="{{ route('admin.attendance.by-course', request('course_id') ?: $courses->first()->id) }}" class="border border-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition">{{ __('messages.by_course') }}</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-5 py-3 text-start">{{ __('messages.student') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.course') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.date') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.status') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.marked_by') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($attendances as $att)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3">
                        <div class="font-medium text-gray-800">{{ $att->user->name ?? '-' }}</div>
                        <div class="text-xs text-gray-400">{{ $att->user->email ?? '' }}</div>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $att->course->title_localized ?? '-' }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ \Carbon\Carbon::parse($att->session_date)->format('d M Y') }}</td>
                    <td class="px-5 py-3">
                        @php $colors = ['present'=>'green','absent'=>'red','late'=>'yellow','excused'=>'blue']; @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs bg-{{ $colors[$att->status] ?? 'gray' }}-100 text-{{ $colors[$att->status] ?? 'gray' }}-700 capitalize">{{ $att->status }}</span>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $att->markedBy->name ?? '-' }}</td>
                    <td class="px-5 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.attendance.edit', $att) }}" class="text-gray-400 hover:text-blue-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.attendance.destroy', $att) }}" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
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
    <div class="p-4 border-t border-gray-100">{{ $attendances->withQueryString()->links() }}</div>
</div>
@endsection
