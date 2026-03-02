@extends('layouts.admin')
@section('title', __('messages.certificates'))
@section('page-title', __('messages.certificates'))

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <form class="flex flex-wrap gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_student_or_number') }}..." class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 w-64">
            <button class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-emerald-700 transition">{{ __('messages.search') }}</button>
        </form>
        <a href="{{ route('admin.certificates.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition shrink-0">+ {{ __('messages.generate_certificate') }}</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-5 py-3 text-start">{{ __('messages.certificate_number') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.student') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.course') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.issued_at') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($certificates as $cert)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3">
                        <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">{{ $cert->certificate_number }}</span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="font-medium text-gray-800">{{ $cert->user->name ?? '-' }}</div>
                        <div class="text-xs text-gray-400">{{ $cert->user->email ?? '' }}</div>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $cert->course->title_localized ?? '-' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ \Carbon\Carbon::parse($cert->issued_at)->format('d M Y') }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.certificates.show', $cert) }}" class="text-emerald-600 hover:text-emerald-700 text-xs font-medium">{{ __('messages.view') }}</a>
                            <a href="{{ route('admin.certificates.download', $cert) }}" class="text-blue-600 hover:text-blue-700 text-xs font-medium">{{ __('messages.download') }}</a>
                            <form method="POST" action="{{ route('admin.certificates.destroy', $cert) }}" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 text-xs">{{ __('messages.delete') }}</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400">{{ __('messages.no_data_yet') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">{{ $certificates->withQueryString()->links() }}</div>
</div>
@endsection
