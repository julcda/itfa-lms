@extends('layouts.admin')
@section('title', $user->name)
@section('page-title', $user->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
            <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-700 text-2xl font-bold mx-auto mb-4">{{ strtoupper(substr($user->name,0,1)) }}</div>
            <h2 class="text-lg font-bold text-gray-800">{{ $user->name }}</h2>
            @if($user->arabic_name)<p class="text-gray-500 text-sm">{{ $user->arabic_name }}</p>@endif
            <p class="text-gray-400 text-sm mt-1">{{ $user->email }}</p>
            @foreach($user->getRoleNames() as $role)
                <span class="inline-block mt-2 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">{{ ucfirst($role) }}</span>
            @endforeach
            <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-2 gap-3 text-center">
                <div><div class="text-xl font-bold text-gray-800">{{ $user->enrollments_count ?? $user->enrollments->count() }}</div><div class="text-xs text-gray-400">{{ __('messages.enrollments') }}</div></div>
                <div><div class="text-xl font-bold text-gray-800">{{ $user->certificates_count ?? $user->certificates->count() }}</div><div class="text-xs text-gray-400">{{ __('messages.certificates') }}</div></div>
            </div>
            <div class="mt-4 flex gap-2">
                <a href="{{ route('admin.users.edit', $user) }}" class="flex-1 bg-emerald-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition">{{ __('messages.edit') }}</a>
                <a href="{{ route('admin.users.index') }}" class="flex-1 border border-gray-200 text-gray-600 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">{{ __('messages.back') }}</a>
            </div>
        </div>
    </div>
    <div class="lg:col-span-2 space-y-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">{{ __('messages.profile_details') }}</h3>
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div><dt class="text-gray-400 text-xs">{{ __('messages.phone') }}</dt><dd class="mt-0.5 text-gray-700">{{ $user->phone ?? '-' }}</dd></div>
                <div><dt class="text-gray-400 text-xs">{{ __('messages.gender') }}</dt><dd class="mt-0.5 text-gray-700">{{ $user->gender ? __('messages.'.$user->gender) : '-' }}</dd></div>
                <div><dt class="text-gray-400 text-xs">{{ __('messages.date_of_birth') }}</dt><dd class="mt-0.5 text-gray-700">{{ optional($user->date_of_birth)->format('d M Y') ?? '-' }}</dd></div>
                <div><dt class="text-gray-400 text-xs">{{ __('messages.language') }}</dt><dd class="mt-0.5 text-gray-700">{{ $user->locale === 'ar' ? 'العربية' : 'English' }}</dd></div>
                <div><dt class="text-gray-400 text-xs">{{ __('messages.status') }}</dt><dd class="mt-0.5"><span class="px-2 py-0.5 rounded-full text-xs {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $user->is_active ? __('messages.active') : __('messages.inactive') }}</span></dd></div>
                <div><dt class="text-gray-400 text-xs">{{ __('messages.joined') }}</dt><dd class="mt-0.5 text-gray-700">{{ $user->created_at->format('d M Y') }}</dd></div>
            </dl>
            @if($user->bio)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <dt class="text-gray-400 text-xs mb-1">{{ __('messages.bio') }}</dt>
                <dd class="text-gray-700 text-sm">{{ $user->bio }}</dd>
            </div>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">{{ __('messages.enrolled_courses') }}</h3>
            @forelse($user->enrollments()->with('course')->latest()->take(5)->get() as $en)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <span class="text-sm text-gray-700">{{ $en->course->title_localized ?? '-' }}</span>
                <span class="text-xs text-gray-400">{{ $en->progress }}%</span>
            </div>
            @empty
            <p class="text-sm text-gray-400">{{ __('messages.no_data_yet') }}</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
