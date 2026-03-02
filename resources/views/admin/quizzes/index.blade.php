@extends('layouts.admin')
@section('title', __('messages.quizzes'))
@section('page-title', __('messages.quizzes'))

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-semibold text-gray-700">{{ __('messages.all_quizzes') }}</h3>
        <a href="{{ route('admin.quizzes.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition">+ {{ __('messages.add_quiz') }}</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-5 py-3 text-start">{{ __('messages.quiz') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.course') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.lesson') }}</th>
                    <th class="px-5 py-3 text-center">{{ __('messages.questions') }}</th>
                    <th class="px-5 py-3 text-center">{{ __('messages.passing_score') }}</th>
                    <th class="px-5 py-3 text-center">{{ __('messages.attempts') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($quizzes as $quiz)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $quiz->title_localized }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $quiz->course->title_localized ?? '-' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $quiz->lesson->title_localized ?? '-' }}</td>
                    <td class="px-5 py-3 text-center text-gray-600">{{ $quiz->questions_count ?? $quiz->questions->count() }}</td>
                    <td class="px-5 py-3 text-center text-gray-600">{{ $quiz->passing_score }}%</td>
                    <td class="px-5 py-3 text-center text-gray-600">{{ $quiz->max_attempts ?? '∞' }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.quizzes.show', $quiz) }}" class="text-gray-400 hover:text-emerald-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="text-gray-400 hover:text-blue-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.quizzes.destroy', $quiz) }}" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
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
    <div class="p-4 border-t border-gray-100">{{ $quizzes->links() }}</div>
</div>
@endsection
