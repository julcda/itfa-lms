@extends('layouts.admin')
@section('title', __('messages.categories'))
@section('page-title', __('messages.categories'))

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Add form --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">{{ __('messages.add_category') }}</h3>
            <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.name_en') }} *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.name_ar') }}</label>
                    <input type="text" name="name_ar" value="{{ old('name_ar') }}" dir="rtl" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.type') }}</label>
                    <select name="type" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        @foreach(['course','book','both'] as $t)
                            <option value="{{ $t }}" {{ old('type','both')===$t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.parent_category') }}</label>
                    <select name="parent_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        <option value="">{{ __('messages.no_parent') }}</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id')==$parent->id ? 'selected' : '' }}>{{ $parent->name_localized }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full bg-emerald-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition">{{ __('messages.add') }}</button>
            </form>
        </div>
    </div>

    {{-- List --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100">
                <h3 class="font-semibold text-gray-700">{{ __('messages.all_categories') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                        <tr>
                            <th class="px-5 py-3 text-start">{{ __('messages.name') }}</th>
                            <th class="px-5 py-3 text-start">{{ __('messages.type') }}</th>
                            <th class="px-5 py-3 text-start">{{ __('messages.parent') }}</th>
                            <th class="px-5 py-3 text-start">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($categories as $cat)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3">
                                <div class="font-medium text-gray-800">{{ $cat->name }}</div>
                                @if($cat->name_ar)<div class="text-xs text-gray-400" dir="rtl">{{ $cat->name_ar }}</div>@endif
                                <div class="text-xs text-gray-300">{{ $cat->slug }}</div>
                            </td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 text-xs capitalize">{{ $cat->type }}</span>
                            </td>
                            <td class="px-5 py-3 text-gray-500 text-xs">{{ $cat->parent->name_localized ?? '-' }}</td>
                            <td class="px-5 py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.categories.edit', $cat) }}" class="text-blue-500 hover:text-blue-700 text-xs">{{ __('messages.edit') }}</a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 text-xs">{{ __('messages.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-5 py-8 text-center text-gray-400">{{ __('messages.no_data_yet') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
