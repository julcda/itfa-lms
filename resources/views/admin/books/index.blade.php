@extends('layouts.admin')
@section('title', __('messages.e_library'))
@section('page-title', __('messages.e_library'))

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <form class="flex flex-wrap gap-2 flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search') }}..." class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 w-48">
            <select name="file_type" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                <option value="">{{ __('messages.all_types') }}</option>
                @foreach(['pdf','video','audio','other','external'] as $t)
                    <option value="{{ $t }}" {{ request('file_type')===$t ? 'selected' : '' }}>{{ strtoupper($t) }}</option>
                @endforeach
            </select>
            <button class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-emerald-700 transition">{{ __('messages.search') }}</button>
        </form>
        <a href="{{ route('admin.books.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition shrink-0">+ {{ __('messages.add_book') }}</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-5 py-3 text-start">{{ __('messages.book') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.author') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.type') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.language') }}</th>
                    <th class="px-5 py-3 text-center">{{ __('messages.views') }}</th>
                    <th class="px-5 py-3 text-center">{{ __('messages.downloads') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($books as $book)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-12 bg-amber-100 rounded overflow-hidden shrink-0">
                                @if($book->cover_image)
                                    <img src="{{ $book->cover_url }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-amber-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">{{ $book->title_localized }}</div>
                                <div class="text-xs text-gray-400">{{ $book->category->name_localized ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $book->author }}</td>
                    <td class="px-5 py-3"><span class="px-2 py-0.5 rounded bg-gray-100 text-gray-600 text-xs uppercase">{{ $book->file_type }}</span></td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $book->language === 'ar' ? 'عربي' : 'EN' }}</td>
                    <td class="px-5 py-3 text-center text-gray-500">{{ number_format($book->view_count) }}</td>
                    <td class="px-5 py-3 text-center text-gray-500">{{ number_format($book->download_count) }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.books.edit', $book) }}" class="text-gray-400 hover:text-blue-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.books.destroy', $book) }}" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">{{ __('messages.no_data_yet') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">{{ $books->withQueryString()->links() }}</div>
</div>
@endsection
