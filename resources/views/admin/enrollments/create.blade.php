@extends('layouts.admin')
@section('title', __('messages.enroll_students'))
@section('page-title', __('messages.enroll_students'))

@section('content')

@php
    $gradeLevels = \App\Models\Course::GRADE_LEVELS;
    $presentGrades = $students->pluck('grade_level')->filter()->unique()->values()->toArray();

    $studentsJson = $students->map(fn($s) => [
        'id'          => $s->id,
        'name'        => $s->name,
        'email'       => $s->email,
        'lrn'         => $s->lrn ?? '',
        'grade_level' => $s->grade_level ?? '',
        'grade_label' => $s->grade_level ? ($gradeLevels[$s->grade_level] ?? $s->grade_level) : 'Unassigned',
        'section'     => $s->section ?? '',
        'initials'    => strtoupper(substr($s->name, 0, 1)),
        'avatar_color'=> ['bg-violet-100 text-violet-700','bg-blue-100 text-blue-700','bg-emerald-100 text-emerald-700','bg-amber-100 text-amber-700','bg-pink-100 text-pink-700'][$s->id % 5],
    ])->values()->toArray();

    $coursesJson = $courses->map(fn($c) => [
        'id'           => $c->id,
        'title'        => $c->title_localized,
        'grade_level'  => $c->grade_level ? ($gradeLevels[$c->grade_level] ?? $c->grade_level) : '',
        'category'     => $c->category?->name ?? '',
        'lessons_count'=> $c->lessons_count ?? $c->lessons->count(),
        'status'       => $c->status ?? 'draft',
    ])->values()->toArray();

    $selectedId = old('course_id', $selectedCourse?->id);
    $preEnrolledJson = $selectedCourse
        ? $selectedCourse->enrollments()->pluck('user_id')->toArray()
        : [];
@endphp

{{-- Breadcrumb --}}
<nav class="text-sm text-gray-500 mb-5 flex items-center gap-2">
    <a href="{{ route('admin.enrollments.index') }}" class="hover:text-violet-600">{{ __('messages.enrollments') }}</a>
    <span>/</span>
    <span class="text-gray-700 font-medium">{{ __('messages.enroll_students') }}</span>
</nav>

@if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
@endif
@if(session('info'))
    <div class="mb-4 bg-blue-50 border border-blue-200 text-blue-700 rounded-xl px-4 py-3 text-sm">{{ session('info') }}</div>
@endif

<form method="POST" action="{{ route('admin.enrollments.store') }}" id="enroll-form">
@csrf
<div class="grid grid-cols-1 xl:grid-cols-5 gap-6">

    {{-- ===== LEFT PANEL (3/5) ===== --}}
    <div class="xl:col-span-3 space-y-5">

        {{-- 1. Course Selector --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <span class="w-6 h-6 bg-violet-100 text-violet-600 rounded-full flex items-center justify-center text-xs font-bold">1</span>
                {{ __('messages.select_course') }}
            </h3>

            <select id="course_select" name="course_id" required
                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-300 @error('course_id') border-red-400 @enderror">
                <option value="">â€” {{ __('messages.select_course') }} â€”</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ $selectedId == $course->id ? 'selected' : '' }}>
                        {{ $course->title_localized }}
                        @if($course->grade_level) Â· {{ $gradeLevels[$course->grade_level] ?? $course->grade_level }}@endif
                    </option>
                @endforeach
            </select>
            @error('course_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

            {{-- Course preview --}}
            <div id="course_preview" class="mt-3 hidden">
                <div class="rounded-lg border border-violet-100 bg-violet-50 px-4 py-3 flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p id="cp_title" class="font-semibold text-violet-800 text-sm"></p>
                        <div class="flex flex-wrap gap-3 mt-1">
                            <span id="cp_grade" class="text-xs text-violet-600 hidden"></span>
                            <span id="cp_category" class="text-xs text-gray-500 hidden"></span>
                            <span id="cp_status" class="text-xs font-medium"></span>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <p id="cp_lessons" class="text-2xl font-bold text-violet-700"></p>
                        <p class="text-xs text-violet-400">{{ __('messages.lessons') }}</p>
                    </div>
                </div>

                {{-- Lessons accordion --}}
                <div class="mt-2">
                    <button type="button" onclick="toggleLessons()"
                        class="text-xs text-violet-600 hover:text-violet-800 font-medium flex items-center gap-1 transition">
                        <svg id="lessons_chevron" class="w-3 h-3 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        {{ __('messages.course_lessons') }}
                    </button>
                    <div id="lessons_list" class="hidden mt-2 rounded-lg border border-gray-100 bg-gray-50 divide-y divide-gray-100 max-h-48 overflow-y-auto text-sm">
                        <div class="px-4 py-3 text-gray-400 text-xs text-center">Loadingâ€¦</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Student Picker --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <span class="w-6 h-6 bg-violet-100 text-violet-600 rounded-full flex items-center justify-center text-xs font-bold">2</span>
                {{ __('messages.select_students') }}
                <span id="selected_counter" class="ml-auto text-xs font-semibold px-2.5 py-1 bg-violet-100 text-violet-700 rounded-full">0 selected</span>
            </h3>

            {{-- Search --}}
            <div class="relative mb-3">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
                <input type="text" id="student_search" placeholder="{{ __('messages.search_student') }}"
                    class="w-full pl-9 pr-8 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-violet-300">
                <button type="button" id="search_clear" onclick="clearSearch()"
                    class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Grade filter tabs --}}
            <div class="flex flex-wrap gap-1.5 mb-3" id="grade_tabs">
                <button type="button" data-grade="" onclick="filterGrade(this)"
                    class="grade-tab active text-xs px-3 py-1.5 rounded-full border font-medium transition">
                    All <span class="grade-count opacity-60"></span>
                </button>
                @foreach($presentGrades as $gl)
                <button type="button" data-grade="{{ $gl }}" onclick="filterGrade(this)"
                    class="grade-tab text-xs px-3 py-1.5 rounded-full border font-medium transition">
                    {{ $gradeLevels[$gl] ?? $gl }} <span class="grade-count opacity-60"></span>
                </button>
                @endforeach
                <button type="button" data-grade="__unassigned__" onclick="filterGrade(this)"
                    class="grade-tab hidden text-xs px-3 py-1.5 rounded-full border font-medium transition">
                    Unassigned <span class="grade-count opacity-60"></span>
                </button>
            </div>

            {{-- Bulk actions --}}
            <div class="flex items-center gap-2 mb-3">
                <button type="button" onclick="selectAllVisible()"
                    class="text-xs px-3 py-1.5 bg-violet-50 text-violet-700 border border-violet-200 rounded-lg hover:bg-violet-100 transition font-medium">
                    âœ“ {{ __('messages.select_all') }}
                </button>
                <button type="button" onclick="deselectAllVisible()"
                    class="text-xs px-3 py-1.5 bg-gray-100 text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-200 transition font-medium">
                    âœ• {{ __('messages.deselect_all') }}
                </button>
                <span class="text-xs text-gray-400 ml-auto" id="visible_count"></span>
            </div>

            {{-- Student Cards --}}
            <div id="student_grid" class="space-y-1.5 max-h-[460px] overflow-y-auto pr-0.5"></div>

            <div id="no_students" class="hidden py-10 text-center text-gray-300">
                <svg class="w-10 h-10 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                </svg>
                <p class="text-sm text-gray-400">No students match your filters</p>
            </div>
        </div>
    </div>

    {{-- ===== RIGHT PANEL (2/5) ===== --}}
    <div class="xl:col-span-2 space-y-5">

        {{-- 3. Summary + Submit --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 sticky top-5">
            <h3 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <span class="w-6 h-6 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-xs font-bold">3</span>
                {{ __('messages.enrollment_summary') }}
            </h3>

            {{-- Stats --}}
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-violet-50 rounded-xl p-3 text-center">
                    <p class="text-2xl font-bold text-violet-700" id="summary_count">0</p>
                    <p class="text-xs text-violet-400 mt-0.5">{{ __('messages.students_selected') }}</p>
                </div>
                <div class="bg-emerald-50 rounded-xl p-3 text-center">
                    <p class="text-2xl font-bold text-emerald-700" id="summary_lessons">â€”</p>
                    <p class="text-xs text-emerald-400 mt-0.5">{{ __('messages.lessons') }}</p>
                </div>
            </div>

            {{-- Selected student chips --}}
            <div class="mb-1">
                <p class="text-xs font-medium text-gray-500 mb-2">{{ __('messages.selected_students') }}</p>
                <div id="selected_chips" class="flex flex-wrap gap-1.5 min-h-[36px] p-2 bg-gray-50 rounded-lg border border-gray-100">
                    <p class="text-xs text-gray-400 italic self-center" id="no_selection_msg">No students selected yet.</p>
                </div>
            </div>

            {{-- Hidden inputs injected by JS - kept close to submit button --}}
            <div id="hidden_inputs"></div>

            {{-- Note --}}
            <div class="bg-amber-50 border border-amber-100 rounded-lg px-3 py-2.5 my-4">
                <p class="text-xs text-amber-700 leading-relaxed">
                    <svg class="w-3.5 h-3.5 inline -mt-0.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Students start from the first lesson. Already-enrolled students are skipped automatically.
                </p>
            </div>

            {{-- Submit --}}
            <div id="submit_error" class="hidden mb-2 bg-red-50 border border-red-200 text-red-600 rounded-lg px-3 py-2 text-xs text-center"></div>
            <button type="submit" id="submit_btn"
                class="w-full py-3 rounded-xl text-sm font-semibold transition-all duration-200 bg-gray-200 text-gray-400">
                <svg class="w-4 h-4 inline -mt-0.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                <span id="submit_label">{{ __('messages.enroll_now') }}</span>
            </button>

            <a href="{{ url()->previous() }}"
                class="block text-center mt-2 text-sm text-gray-400 hover:text-gray-600 transition py-1">
                â† {{ __('messages.cancel') }}
            </a>
        </div>
    </div>

</div>
</form>

@push('styles')
<style>
.grade-tab { background:#f9fafb; border-color:#e5e7eb; color:#6b7280; }
.grade-tab:hover { background:#ede9fe; border-color:#c4b5fd; color:#6d28d9; }
.grade-tab.active { background:#ede9fe; border-color:#8b5cf6; color:#5b21b6; }

.student-card {
    display:flex; align-items:center; gap:10px;
    padding:10px 12px; border-radius:10px;
    border:1.5px solid #e5e7eb; cursor:pointer;
    transition:all .15s; user-select:none; background:#fff;
}
.student-card:hover { border-color:#8b5cf6; background:#faf5ff; }
.student-card.selected { border-color:#7c3aed; background:#f5f3ff; }
.student-card.already-enrolled { opacity:.6; cursor:not-allowed; background:#f0fdf4; border-color:#bbf7d0; }
.student-card.hidden-card { display:none !important; }

.check-circle {
    width:18px; height:18px; border-radius:50%;
    border:2px solid #d1d5db; display:flex;
    align-items:center; justify-content:center;
    flex-shrink:0; transition:all .15s;
}
.student-card.selected .check-circle { background:#7c3aed; border-color:#7c3aed; }
.check-icon { display:none; }
.student-card.selected .check-icon { display:block; }

.chip {
    display:inline-flex; align-items:center; gap:3px;
    background:#ede9fe; color:#5b21b6;
    border-radius:999px; padding:3px 8px;
    font-size:11px; font-weight:500; max-width:140px;
}
.chip span.chip-name { overflow:hidden; text-overflow:ellipsis; white-space:nowrap; flex:1; }
.chip button { color:#7c3aed; background:none; border:none; cursor:pointer; padding:0; font-size:13px; line-height:1; flex-shrink:0; }
.chip button:hover { color:#4c1d95; }

@keyframes shake {
    0%,100%{transform:translateX(0)}
    20%{transform:translateX(-6px)}
    40%{transform:translateX(6px)}
    60%{transform:translateX(-4px)}
    80%{transform:translateX(4px)}
}
.shake { animation: shake .4s ease; }
</style>
@endpush

@push('scripts')
<script>
const ALL_STUDENTS    = @json($studentsJson);
const ALL_COURSES     = @json($coursesJson);
let ALREADY_ENROLLED  = @json($preEnrolledJson);
let selectedIds       = new Set();
let currentGrade      = '';
let currentSearch     = '';
let lessonsOpen       = false;

// â”€â”€ Bootstrap â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
document.addEventListener('DOMContentLoaded', () => {
    buildStudentGrid();
    updateGradeCounts();
    updateSummary(); // set correct initial label + button state

    document.getElementById('student_search').addEventListener('input', function () {
        currentSearch = this.value.trim().toLowerCase();
        document.getElementById('search_clear').classList.toggle('hidden', !currentSearch);
        applyFilters();
    });

    const sel = document.getElementById('course_select');
    if (sel.value) triggerCourseSelect(sel.value);
    sel.addEventListener('change', () => triggerCourseSelect(sel.value));

    // Guard: prevent submit if requirements not met, show friendly error
    document.getElementById('enroll-form').addEventListener('submit', function(e) {
        // Always re-inject hidden inputs fresh at submit time
        const hiddenDiv = document.getElementById('hidden_inputs');
        hiddenDiv.innerHTML = '';
        selectedIds.forEach(id => {
            const inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = 'user_ids[]';
            inp.value = id;
            hiddenDiv.appendChild(inp);
        });

        const hasCourse   = !!document.getElementById('course_select').value;
        const hasStudents = selectedIds.size > 0;
        const errEl       = document.getElementById('submit_error');
        if (!hasCourse || !hasStudents) {
            e.preventDefault();
            const msg = !hasCourse && !hasStudents
                ? 'Please select a course and at least one student.'
                : !hasCourse ? 'Please select a course first.'
                : 'Please select at least one student to enroll.';
            errEl.textContent = msg;
            errEl.classList.remove('hidden');
            const btn = document.getElementById('submit_btn');
            btn.classList.add('shake');
            setTimeout(() => { btn.classList.remove('shake'); }, 500);
            errEl.scrollIntoView({behavior:'smooth', block:'center'});
        }
    });
});

// â”€â”€ Student Grid â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function buildStudentGrid() {
    const grid = document.getElementById('student_grid');
    grid.innerHTML = '';
    ALL_STUDENTS.forEach(s => {
        const enrolled = ALREADY_ENROLLED.includes(s.id);
        const div = document.createElement('div');
        div.className = 'student-card' + (enrolled ? ' already-enrolled' : '') + (!enrolled && selectedIds.has(s.id) ? ' selected' : '');
        div.dataset.id    = s.id;
        div.dataset.grade = s.grade_level;
        div.dataset.name  = s.name.toLowerCase();
        div.dataset.email = s.email.toLowerCase();
        div.dataset.lrn   = s.lrn.toLowerCase();
        if (!enrolled) div.onclick = () => toggleStudent(s.id);

        div.innerHTML = `
            <div class="check-circle">
                <svg class="check-icon w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div class="w-8 h-8 rounded-full ${s.avatar_color} flex items-center justify-center text-sm font-bold flex-shrink-0">${s.initials}</div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-1.5 flex-wrap">
                    <span class="text-sm font-medium text-gray-800">${s.name}</span>
                    ${enrolled ? '<span class="text-xs bg-green-100 text-green-600 rounded-full px-2 py-0.5 font-medium">Enrolled</span>' : ''}
                </div>
                <div class="flex flex-wrap gap-x-3 mt-0.5">
                    <span class="text-xs text-gray-400">${s.email}</span>
                    ${s.lrn ? `<span class="text-xs text-gray-400">LRN: ${s.lrn}</span>` : ''}
                </div>
            </div>
            <div class="text-right flex-shrink-0">
                ${s.grade_label !== 'Unassigned' ? `<p class="text-xs font-medium text-gray-600 whitespace-nowrap">${s.grade_label}</p>` : ''}
                ${s.section ? `<p class="text-xs text-gray-400">${s.section}</p>` : ''}
            </div>`;
        grid.appendChild(div);
    });
    applyFilters();
}

// â”€â”€ Toggle â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function toggleStudent(id) {
    const card = document.querySelector(`.student-card[data-id="${id}"]`);
    if (!card || card.classList.contains('already-enrolled')) return;
    if (selectedIds.has(id)) { selectedIds.delete(id); card.classList.remove('selected'); }
    else                     { selectedIds.add(id);    card.classList.add('selected'); }
    updateSummary();
}

function removeStudent(id) {
    selectedIds.delete(id);
    const card = document.querySelector(`.student-card[data-id="${id}"]`);
    if (card) card.classList.remove('selected');
    updateSummary();
}

// â”€â”€ Filters â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function filterGrade(btn) {
    document.querySelectorAll('.grade-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    currentGrade = btn.dataset.grade;
    applyFilters();
}

function applyFilters() {
    const cards = document.querySelectorAll('.student-card');
    let visible = 0;
    cards.forEach(card => {
        const g = card.dataset.grade;
        const matchGrade = !currentGrade ? true
            : currentGrade === '__unassigned__' ? !g
            : g === currentGrade;
        const matchSearch = !currentSearch ? true
            : card.dataset.name.includes(currentSearch) ||
              card.dataset.email.includes(currentSearch) ||
              card.dataset.lrn.includes(currentSearch);
        const show = matchGrade && matchSearch;
        card.classList.toggle('hidden-card', !show);
        if (show) visible++;
    });
    document.getElementById('no_students').classList.toggle('hidden', visible > 0);
    document.getElementById('visible_count').textContent = visible > 0 ? `${visible} shown` : '';
}

function clearSearch() {
    document.getElementById('student_search').value = '';
    currentSearch = '';
    document.getElementById('search_clear').classList.add('hidden');
    applyFilters();
}

// â”€â”€ Bulk select â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function selectAllVisible() {
    document.querySelectorAll('.student-card:not(.hidden-card):not(.already-enrolled)').forEach(card => {
        selectedIds.add(parseInt(card.dataset.id));
        card.classList.add('selected');
    });
    updateSummary();
}
function deselectAllVisible() {
    document.querySelectorAll('.student-card:not(.hidden-card):not(.already-enrolled)').forEach(card => {
        selectedIds.delete(parseInt(card.dataset.id));
        card.classList.remove('selected');
    });
    updateSummary();
}

// â”€â”€ Grade counts â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function updateGradeCounts() {
    const counts = {};
    let unassigned = 0;
    ALL_STUDENTS.forEach(s => {
        if (s.grade_level) counts[s.grade_level] = (counts[s.grade_level] || 0) + 1;
        else unassigned++;
    });
    document.querySelectorAll('.grade-tab').forEach(t => {
        const g = t.dataset.grade;
        const span = t.querySelector('.grade-count');
        if (g === '')              span.textContent = `(${ALL_STUDENTS.length})`;
        else if (g === '__unassigned__') { span.textContent = `(${unassigned})`; if (unassigned > 0) t.classList.remove('hidden'); }
        else span.textContent = counts[g] ? `(${counts[g]})` : '';
    });
}

// â”€â”€ Summary â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function updateSummary() {
    const count = selectedIds.size;
    document.getElementById('selected_counter').textContent = `${count} selected`;
    document.getElementById('summary_count').textContent = count;

    const chipsDiv  = document.getElementById('selected_chips');
    const noMsg     = document.getElementById('no_selection_msg');
    chipsDiv.innerHTML  = '';

    if (count === 0) {
        noMsg.style.display = '';
    } else {
        noMsg.style.display = 'none';
        selectedIds.forEach(id => {
            const s = ALL_STUDENTS.find(x => x.id === id);
            if (!s) return;
            const chip = document.createElement('span');
            chip.className = 'chip';
            chip.innerHTML = `<span class="chip-name">${s.name}</span><button type="button" onclick="removeStudent(${id})" title="Remove">Ã—</button>`;
            chipsDiv.appendChild(chip);
        });
    }

    const btn       = document.getElementById('submit_btn');
    const hasCourse  = !!document.getElementById('course_select').value;
    const canSubmit  = count > 0 && hasCourse;

    if (canSubmit) {
        btn.style.cssText = 'background:linear-gradient(135deg,#7c3aed,#5b21b6);color:#fff;cursor:pointer;transform:none;box-shadow:0 4px 14px rgba(124,58,237,.35);';
        document.getElementById('submit_error').classList.add('hidden');
    } else {
        btn.style.cssText = 'background:#e5e7eb;color:#9ca3af;cursor:default;';
    }

    document.getElementById('submit_label').textContent =
        !hasCourse    ? '① Select a course above first'
        : count === 0 ? '② Select at least one student'
        :               `Enroll ${count} Student${count > 1 ? 's' : ''} Now →`;
}

// â”€â”€ Course select â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function triggerCourseSelect(courseId) {
    const preview = document.getElementById('course_preview');
    if (!courseId) { preview.classList.add('hidden'); updateSummary(); return; }

    const c = ALL_COURSES.find(x => x.id == courseId);
    if (c) {
        preview.classList.remove('hidden');
        document.getElementById('cp_title').textContent = c.title;
        const gradeEl = document.getElementById('cp_grade');
        const catEl   = document.getElementById('cp_category');
        gradeEl.classList.toggle('hidden', !c.grade_level);
        if (c.grade_level) gradeEl.textContent = c.grade_level;
        catEl.classList.toggle('hidden', !c.category);
        if (c.category) catEl.textContent = c.category;
        document.getElementById('cp_lessons').textContent = c.lessons_count;
        const sc = {published:'text-emerald-600', draft:'text-gray-400', inactive:'text-red-500'};
        const sEl = document.getElementById('cp_status');
        sEl.textContent = c.status.charAt(0).toUpperCase() + c.status.slice(1);
        sEl.className = `text-xs font-medium ${sc[c.status] || 'text-gray-500'}`;
    }

    // Reset already-enrolled for new course, then rebuild
    fetch(`{{ url('admin/courses') }}/${courseId}/enrollments-json`, {
        headers:{'X-Requested-With':'XMLHttpRequest'}
    })
    .then(r => r.json())
    .then(ids => {
        ALREADY_ENROLLED = ids;
        // Deselect any student now found to be already enrolled
        ids.forEach(id => { selectedIds.delete(id); });
        buildStudentGrid(); // rebuild cards with updated enrolled state
        updateSummary();
    })
    .catch(() => { /* no dedicated endpoint yet, keep existing */ updateSummary(); });

    // Load lessons preview
    fetch(`{{ url('admin/courses') }}/${courseId}/lessons-json`, {
        headers:{'X-Requested-With':'XMLHttpRequest'}
    })
    .then(r => r.json())
    .then(lessons => {
        document.getElementById('summary_lessons').textContent = lessons.length || '0';
        const list = document.getElementById('lessons_list');
        if (!lessons.length) {
            list.innerHTML = '<div class="px-4 py-3 text-xs text-gray-400 text-center">No lessons yet.</div>';
            // Auto-open to show the message
            list.classList.remove('hidden');
            lessonsOpen = true;
            document.getElementById('lessons_chevron').style.transform = 'rotate(90deg)';
            return;
        }
        // Auto-open accordion when lessons are loaded
        list.classList.remove('hidden');
        lessonsOpen = true;
        document.getElementById('lessons_chevron').style.transform = 'rotate(90deg)';
        const statusColors = {active:'text-emerald-500', draft:'text-gray-400', inactive:'text-red-400'};
        list.innerHTML = lessons.map((l, i) =>
            `<div class="px-4 py-2.5 flex items-center gap-3">
                <span class="w-5 h-5 bg-white border border-gray-200 rounded-full flex items-center justify-center text-xs font-bold text-gray-500 flex-shrink-0">${l.order || (i+1)}</span>
                <span class="text-sm text-gray-700 flex-1 truncate">${l.title}</span>
                ${l.duration_minutes ? `<span class="text-xs text-gray-400">${l.duration_minutes}m</span>` : ''}
                ${l.is_free ? '<span class="text-xs bg-green-100 text-green-600 rounded-full px-1.5 py-0.5 font-medium">free</span>' : ''}
                <span class="text-xs ${statusColors[l.status]||'text-gray-400'}">${l.status||''}</span>
            </div>`
        ).join('');
    })
    .catch(err => { console.error('lessons-json error:', err); });
}

function toggleLessons() {
    lessonsOpen = !lessonsOpen;
    document.getElementById('lessons_list').classList.toggle('hidden', !lessonsOpen);
    document.getElementById('lessons_chevron').style.transform = lessonsOpen ? 'rotate(90deg)' : 'rotate(0deg)';
}
</script>
@endpush

@endsection
