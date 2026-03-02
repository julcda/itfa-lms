@extends('layouts.admin')
@section('title', __('messages.mark_attendance'))
@section('page-title', __('messages.mark_attendance'))

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <form method="POST" action="{{ route('admin.attendance.store') }}" class="space-y-5">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.course') }} *</label>
                <select name="course_id" required id="crs" onchange="this.form.submit()" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    <option value="">{{ __('messages.select_course') }}</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id')==$course->id ? 'selected' : '' }}>{{ $course->title_localized }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.session_date') }} *</label>
                <input type="date" name="session_date" value="{{ request('session_date', date('Y-m-d')) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
            </div>
        </div>

        @if(isset($students) && $students->count())
        <div class="border border-gray-200 rounded-xl overflow-hidden">
            <div class="bg-gray-50 px-5 py-3 flex items-center justify-between">
                <span class="text-sm font-medium text-gray-700">{{ __('messages.students') }} ({{ $students->count() }})</span>
                <div class="flex gap-2">
                    @foreach(['present','absent','late','excused'] as $s)
                        <button type="button" onclick="setAll('{{ $s }}')" class="px-2 py-1 text-xs bg-white border border-gray-200 rounded hover:bg-gray-50">{{ __('messages.'.$s) }}</button>
                    @endforeach
                </div>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($students as $student)
                <div class="flex items-center justify-between px-5 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-700 text-xs font-bold">{{ strtoupper(substr($student->name,0,1)) }}</div>
                        <div>
                            <div class="text-sm font-medium text-gray-800">{{ $student->name }}</div>
                            <div class="text-xs text-gray-400">{{ $student->email }}</div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        @foreach(['present','absent','late','excused'] as $s)
                            @php $colors = ['present'=>'green','absent'=>'red','late'=>'yellow','excused'=>'blue']; @endphp
                            <label class="flex items-center gap-1 cursor-pointer">
                                <input type="radio" name="statuses[{{ $student->id }}]" value="{{ $s }}" {{ $s === 'present' ? 'checked' : '' }} class="sr-only status-radio-{{ $student->id }}">
                                <span class="px-2 py-1 text-xs rounded-full border border-{{ $colors[$s] }}-200 text-{{ $colors[$s] }}-600 cursor-pointer hover:bg-{{ $colors[$s] }}-50 transition status-label status-{{ $s }}" id="lbl_{{ $student->id }}_{{ $s }}">{{ __('messages.'.$s) }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition">{{ __('messages.save_attendance') }}</button>
            <a href="{{ route('admin.attendance.index') }}" class="border border-gray-200 text-gray-600 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">{{ __('messages.cancel') }}</a>
        </div>
        @elseif(request('course_id'))
            <div class="text-center py-6 text-gray-400 text-sm">{{ __('messages.no_students_enrolled') }}</div>
        @endif
    </form>
</div>
@endsection
@push('scripts')
<script>
document.querySelectorAll('input[type=radio]').forEach(radio => {
    radio.addEventListener('change', function() {
        const name = this.name;
        document.querySelectorAll(`input[name="${name}"]`).forEach(r => {
            document.getElementById(`lbl_${name.match(/\d+/)[0]}_${r.value}`).classList.remove('bg-green-100','bg-red-100','bg-yellow-100','bg-blue-100','font-bold');
        });
        document.getElementById(`lbl_${name.match(/\d+/)[0]}_${this.value}`).classList.add(`bg-${{'present':'green','absent':'red','late':'yellow','excused':'blue'}[this.value]}-100`, 'font-bold');
    });
    if(radio.checked) radio.dispatchEvent(new Event('change'));
});
function setAll(status) {
    document.querySelectorAll(`input[type=radio][value="${status}"]`).forEach(r => { r.checked = true; r.dispatchEvent(new Event('change')); });
}
</script>
@endpush
