@extends('layouts.admin')
@section('title', __('messages.certificate').' #'.$certificate->certificate_number)
@section('page-title', __('messages.certificate_details'))

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-700 to-emerald-600 p-6 text-white text-center">
            <div class="text-sm text-emerald-100 mb-1">{{ __('messages.certificate_of_completion') }}</div>
            <div class="font-mono text-white text-lg font-bold tracking-widest">{{ $certificate->certificate_number }}</div>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-gray-400 text-xs mb-0.5">{{ __('messages.student') }}</dt>
                    <dd class="font-semibold text-gray-800">{{ $certificate->user->name ?? '-' }}</dd>
                    @if($certificate->user->arabic_name)<dd class="text-gray-500 text-xs" dir="rtl">{{ $certificate->user->arabic_name }}</dd>@endif
                </div>
                <div>
                    <dt class="text-gray-400 text-xs mb-0.5">{{ __('messages.course') }}</dt>
                    <dd class="font-semibold text-gray-800">{{ $certificate->course->title_localized ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400 text-xs mb-0.5">{{ __('messages.issued_at') }}</dt>
                    <dd class="text-gray-700">{{ \Carbon\Carbon::parse($certificate->issued_at)->format('d F Y') }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400 text-xs mb-0.5">{{ __('messages.issued_by') }}</dt>
                    <dd class="text-gray-700">{{ __('messages.app_name') }}</dd>
                </div>
            </div>
            <div class="flex gap-3 pt-2 border-t border-gray-100 mt-4">
                <a href="{{ route('admin.certificates.download', $certificate) }}" class="bg-emerald-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    {{ __('messages.download_pdf') }}
                </a>
                <a href="{{ route('admin.certificates.index') }}" class="border border-gray-200 text-gray-600 px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">{{ __('messages.back') }}</a>
                <form method="POST" action="{{ route('admin.certificates.destroy', $certificate) }}" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                    @csrf @method('DELETE')
                    <button type="submit" class="border border-red-200 text-red-600 px-5 py-2 rounded-lg text-sm hover:bg-red-50 transition">{{ __('messages.delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
