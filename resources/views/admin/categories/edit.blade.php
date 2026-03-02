@extends('layouts.admin')
@section('title', __('messages.edit_category'))
@section('page-title', __('messages.edit_category'))

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.name_en') }} *</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.name_ar') }}</label>
                <input type="text" name="name_ar" value="{{ old('name_ar', $category->name_ar) }}" dir="rtl" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.type') }}</label>
                <select name="type" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    @foreach(['course','book','both'] as $t)
                        <option value="{{ $t }}" {{ old('type',$category->type)===$t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.parent_category') }}</label>
                <select name="parent_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    <option value="">{{ __('messages.no_parent') }}</option>
                    @foreach($parents as $parent)
                        @if($parent->id !== $category->id)
                        <option value="{{ $parent->id }}" {{ old('parent_id',$category->parent_id)==$parent->id ? 'selected' : '' }}>{{ $parent->name_localized }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition">{{ __('messages.update') }}</button>
                <a href="{{ route('admin.categories.index') }}" class="border border-gray-200 text-gray-600 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">{{ __('messages.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
