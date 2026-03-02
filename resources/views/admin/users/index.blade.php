@extends('layouts.admin')
@section('title', __('messages.users'))
@section('page-title', __('messages.users'))

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <form class="flex gap-2 flex-1 max-w-md">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search') }}..." class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
            <select name="role" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                <option value="">{{ __('messages.all_roles') }}</option>
                @foreach(['admin','teacher','student'] as $r)
                    <option value="{{ $r }}" {{ request('role')===$r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                @endforeach
            </select>
            <button class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-emerald-700 transition">{{ __('messages.search') }}</button>
        </form>
        <a href="{{ route('admin.users.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition shrink-0">+ {{ __('messages.add_user') }}</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-5 py-3 text-start">#</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.name') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.email') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.role') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.status') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.created_at') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3 text-gray-400">{{ $user->id }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-700 text-xs font-bold shrink-0">{{ strtoupper(substr($user->name,0,1)) }}</div>
                            <div>
                                <div class="font-medium text-gray-800">{{ $user->name }}</div>
                                @if($user->arabic_name)<div class="text-xs text-gray-400">{{ $user->arabic_name }}</div>@endif
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $user->email }}</td>
                    <td class="px-5 py-3">
                        @foreach($user->getRoleNames() as $role)
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs bg-emerald-100 text-emerald-700">{{ $role }}</span>
                        @endforeach
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $user->is_active ? __('messages.active') : __('messages.inactive') }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-400 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.users.show', $user) }}" class="text-gray-400 hover:text-emerald-600 transition" title="{{ __('messages.view') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-gray-400 hover:text-blue-600 transition" title="{{ __('messages.edit') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            @if(auth()->id() !== $user->id)
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600 transition" title="{{ __('messages.delete') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">{{ __('messages.no_data_yet') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">
        {{ $users->withQueryString()->links() }}
    </div>
</div>
@endsection
