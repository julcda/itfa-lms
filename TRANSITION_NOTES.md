# LMS Project — Transition Notes
**Last updated:** February 25, 2026  
**Environment:** XAMPP · PHP 8.2.12 · Laravel 12.52.0 · MySQL (`lms_itfa`) · Tailwind CSS via Vite  
**Root path:** `c:\xampp\htdocs\lms`  
**PHP binary:** `C:\xampp\php\php.exe`

---

## 1. Project Overview

A **DepEd K-12 Learning Management System** built with Laravel + Blade + Tailwind CSS.  
It supports three roles (via Spatie Permission): `admin`, `teacher`, `student`.

| URL prefix | Role | Layout |
|---|---|---|
| `/admin/*` | admin, teacher | `layouts/admin.blade.php` (emerald-800 sidebar) |
| `/student/*` | student | `layouts/lms.blade.php` (emerald-800 top nav) |
| `/` | guest/public | `layouts/guest.blade.php` (split-screen) |

---

## 2. Tech Stack & Key Packages

| Package | Purpose |
|---|---|
| `spatie/laravel-permission` | Roles & permissions (`admin`, `teacher`, `student`) |
| `barryvdh/laravel-dompdf` | PDF certificate generation |
| `intervention/image` | Image processing (covers/avatars) |
| Tailwind CSS (via Vite) | Styling — primary color `emerald-800` |

---

## 3. Database

**Connection:** MySQL, database `lms_itfa`

### Migrations (in order)
| File | Table |
|---|---|
| `0001_01_01_000000` | `users` |
| `0001_01_01_000001` | `cache` |
| `0001_01_01_000002` | `jobs` |
| `2026_02_22_053130` | `permission_tables` (Spatie) |
| `2026_02_22_053152` | `users` — add profile fields |
| `2026_02_22_053152` | `categories` |
| `2026_02_22_053152` | `courses` |
| `2026_02_22_053153` | `enrollments` |
| `2026_02_22_053153` | `lessons` |
| `2026_02_22_053227` | `books` |
| `2026_02_22_053227` | `quizzes` |
| `2026_02_22_053227` | `quiz_questions` |
| `2026_02_22_053228` | `attendances` |
| `2026_02_22_053228` | `quiz_attempts` |
| `2026_02_22_053229` | `certificates` |
| `2026_02_22_100000` | `courses` — add K-12 DepEd fields |
| `2026_02_22_100001` | `books` — add K-12 DepEd fields |
| `2026_02_22_100002` | `users` — add K-12 DepEd fields |
| `2026_02_25_000001` | `settings` |

### Key Table Fields

**`books`** — `title`, `title_ar`, `author`, `author_ar`, `description`, `description_ar`, `category_id`, `cover_image`, `file_path`, `file_type` (pdf/epub/doc/video/audio/external/other), `external_url`, `isbn`, `published_year`, `language`, `tags`(JSON), `status` (active/inactive), `uploaded_by`, `download_count`, `view_count`, `grade_level`, `learning_area`, `deped_code`, `edition`

**`courses`** — `title`, `title_ar`, `slug`, `description`, `description_ar`, `category_id`, `teacher_id`, `thumbnail`, `status`, `level`, `duration_hours`, `is_featured`, `grade_level`, `learning_area`, `quarter`, `school_year`, `strand`, `subject_code`

**`users`** — standard auth fields + `arabic_name`, `avatar`, `phone`, `bio`, `gender`, `date_of_birth`, `locale`, `is_active`, `lrn`, `grade_level`, `section`, `strand`, `school_year`

**`settings`** — `key`, `value`, `group`

---

## 4. Application Structure

### Models (`app/Models/`)
| Model | Notable Accessors / Methods |
|---|---|
| `User` | `display_name` (AR/EN), `avatar_url`, HasRoles |
| `Course` | `title_localized`, `thumbnail_url`, GRADE_LEVELS / LEARNING_AREAS / STRANDS / QUARTERS const arrays |
| `Book` | `title_localized`, `cover_url`, tags cast as array |
| `Category` | `name_localized` |
| `Lesson` | belongs to Course |
| `Quiz` / `QuizQuestion` / `QuizAttempt` | — |
| `Enrollment` | pivot: `progress`, `status`, `enrolled_at`, `completed_at` |
| `Attendance` | — |
| `Certificate` | — |
| `Setting` | `Setting::get($key, $default)`, `Setting::set($key, $value, $group)`, `Setting::allKeyed()` — values cached with `Cache::rememberForever` |

### Helpers (`app/helpers.php`)
Auto-loaded via `AppServiceProvider::register()`.
- `setting(string $key, $default)` — wrapper for `Setting::get()`
- `school_name()` — returns `setting('school_name', config('app.name'))`

### Controllers

**Admin** (`app/Http/Controllers/Admin/`)
- `DashboardController` — stats overview
- `UserController` — full CRUD, role assignment
- `CategoryController` — CRUD
- `CourseController` — CRUD with K-12 fields
- `LessonController` — nested under courses
- `EnrollmentController` — manage enrollments; AJAX endpoints for lessons/students
- `BookController` — CRUD with file/cover upload, status, K-12 fields
- `QuizController` / `QuizQuestionController` — quiz management
- `AttendanceController` — by student and by course
- `CertificateController` — generate (dompdf), download
- `SettingsController` — school branding settings

**Student** (`app/Http/Controllers/Student/`)
- `DashboardController` — enrolled courses, progress, recent books
- `CourseController` — index, show, lesson view, attachment download
- `BookController` — index (with sidebar filters), show, download
- `QuizController` — show, start, submit, result
- `CertificateController` — index, download

---

## 5. Routes Summary (`routes/web.php`)

```
GET  /                              home
GET  /locale/{locale}               locale.switch

GET  /dashboard                     → redirects by role

# Admin (role: admin|teacher)
GET|POST   /admin/dashboard
GET|POST   /admin/users/{...}       (admin only)
GET|POST   /admin/categories/{...}
GET|PUT    /admin/settings          (admin only)
GET|POST   /admin/courses/{...}
GET|POST   /admin/courses/{course}/lessons/{...}
GET|POST   /admin/enrollments/{...}
GET        /admin/courses/{course}/lessons-json
GET        /admin/courses/{course}/enrollments-json
GET|POST   /admin/books/{...}
GET|POST   /admin/quizzes/{...}
GET|POST   /admin/quizzes/{quiz}/questions/{...}
GET|POST   /admin/attendance/{...}
GET        /admin/attendance/course/{course}
GET|POST   /admin/certificates/{...}
POST       /admin/certificates/{user}/{course}/generate
GET        /admin/certificates/{certificate}/download

# Student (role: student)
GET  /student/dashboard             student.dashboard
GET  /student/courses               student.courses.index
GET  /student/courses/{course}      student.courses.show
GET  /student/courses/{course}/lessons/{lesson}         student.courses.lesson
GET  /student/courses/{course}/lessons/{lesson}/download
GET  /student/library               student.library.index
GET  /student/library/{book}        student.library.show
GET  /student/library/{book}/download  student.library.download
GET  /student/quizzes/{quiz}        student.quizzes.show
POST /student/quizzes/{quiz}/start
POST /student/quizzes/{quiz}/submit
GET  /student/quizzes/{quiz}/result/{attempt}
GET  /student/certificates          student.certificates.index
GET  /student/certificates/{certificate}/download
```

---

## 6. Views (`resources/views/`)

```
layouts/
  admin.blade.php       — emerald sidebar, used by all admin pages
  lms.blade.php         — emerald top nav, used by all student pages
  guest.blade.php       — split-screen auth layout (left: brand, right: form)
  app.blade.php         — Breeze default (not primary)
  navigation.blade.php  — legacy Breeze nav

auth/
  login.blade.php       — redesigned split-screen emerald form
  register.blade.php    — redesigned split-screen emerald form
  forgot-password.blade.php
  reset-password.blade.php
  verify-email.blade.php

home.blade.php          — public landing page

admin/
  dashboard.blade.php
  books/      create | edit | index
  categories/ edit | index
  certificates/ create | index | show | pdf
  courses/    create | edit | index | show
  enrollments/ create | index
  lessons/    create | edit | show
  quiz-questions/ create | edit
  quizzes/    create | edit | index | show
  attendances/ by-course | create | edit | index
  users/      create | edit | index | show
  settings/   index

student/
  dashboard.blade.php
  courses/    index | show | lesson
  library/
    index.blade.php     — ★ RECENTLY REDESIGNED (sidebar filters + grid/list)
    show.blade.php      — ★ RECENTLY REDESIGNED (dark hero, metadata sidebar)
  quizzes/    show | take | result
  certificates/ index
```

---

## 7. Completed Features (Session History)

### ✅ Bug Fixes
- **Books create/edit** — `status` dropdown missing → added to both forms
- **Books file_type** — `other` was not in validation `in:` rule → fixed in `store()` and `update()`

### ✅ School Settings Module
- Migration: `settings` table (`key`, `value`, `group`)
- Model: `app/Models/Setting.php` — `get()` / `set()` with `Cache::rememberForever`
- Helpers: `app/helpers.php` — `setting()` and `school_name()` global functions
- Controller: `Admin\SettingsController` — index + update
- View: `admin/settings/index.blade.php`
- Route: `GET|PUT /admin/settings` (admin only)
- Used in: layouts, home page, sidebar, page titles

### ✅ Auth / Login Redesign
- Layout: `layouts/guest.blade.php` — split-screen (emerald left panel + white right form)
- `auth/login.blade.php` and `auth/register.blade.php` fully restyled
- `components/text-input.blade.php` and `components/primary-button.blade.php` updated to emerald theme

### ✅ Student E-Library — Full Redesign

**Controller** (`Student\BookController@index`):
```php
// Filters: search, category_id, file_type, grade_level
// Sort: latest (default), popular, downloads, title
// Returns: $books (paginated 20), $categories (withCount books),
//          $gradeLevels (distinct values), $stats (total/downloads/categories),
//          $typeCounts (file_type → count), $featuredBooks (empty collect)
```

**`student/library/index.blade.php`** (current design):
- Emerald gradient header banner with 3 stat chips
- Left sidebar (hidden on mobile): Search, Category filter (with counts), Grade Level filter, File Type filter, Clear filters
- Toolbar: active filter chips (×-removable), sort dropdown, grid/list toggle
- Mobile: inline search + category + grade-level dropdown row
- **Grid view** — `grid-cols-3 sm:grid-cols-4 lg:cols-4 xl:cols-5 2xl:cols-6`, `padding-top:115%` covers, gradient fallbacks, type badge, grade badge, hover overlay. **Category name is a clickable filter link.**
- **List view** — table with cover thumbnail, title+author, category (clickable filter link), grade level badge, type badge, Open button
- Empty state with clear-filters CTA
- Pagination

**`student/library/show.blade.php`** (current design):
- Dark emerald hero with book cover, type/category/language badges
- Two-column: description + tags (left) | metadata + stats sidebar (right)
- Action buttons: Read/Watch/Listen and Download

---

## 8. Localisation

**Languages:** English (`lang/en/messages.php`) and Arabic (`lang/ar/messages.php`)  
**Locale switching:** `GET /locale/{locale}` → `LocaleController`  
**Per-user locale:** stored in `users.locale` column, applied at login  
**Bilingual fields:** `title`/`title_ar`, `description`/`description_ar`, `author`/`author_ar`, `name`/`arabic_name` — resolved via `*_localized` accessors checking `app()->getLocale() === 'ar'`

### Recently Added Lang Keys (both EN + AR)
`digital_library`, `library_subtitle`, `library_hero_title`, `total_books`, `total_downloads`, `newly_added`, `sort_latest`, `sort_popular`, `sort_downloads`, `sort_title`, `found`, `clear_filters`, `filter_by`, `try_different_filters`, `browse_all`, `open`, `details`, `format`, `thumbnail`, `view_details`, `all_types`

---

## 9. Styling Conventions

```
Primary color:     emerald-800 (nav/sidebar), emerald-600 (buttons/accents)
Body background:   bg-gray-50
Cards:             bg-white, border border-gray-200, rounded-2xl, shadow-sm
Section headers:   bg-gray-50 border-b border-gray-100, text-[10px] uppercase tracking-widest
Danger:            red-600
Warning/Grade:     amber-50/amber-700 border-amber-200
Category filter:   violet-50/violet-700
Search/text filter: emerald-50/emerald-700
```

### Book Cover Gradients (by `$book->id % 8`)
```css
cvr-0: #667eea → #764ba2   cvr-4: #fa709a → #fee140
cvr-1: #f093fb → #f5576c   cvr-5: #a18cd1 → #fbc2eb
cvr-2: #4facfe → #00f2fe   cvr-6: #fda085 → #f6d365
cvr-3: #43e97b → #38f9d7   cvr-7: #89f7fe → #66a6ff
```

### File Type Badge Classes
`.tb-pdf` `.tb-video` `.tb-audio` `.tb-epub` `.tb-doc` `.tb-external` `.tb-other`  
Pattern: `<span class="tb tb-{{ strtolower($book->file_type) }}">{{ strtoupper($book->file_type) }}</span>`

---

## 10. Important Implementation Notes

### Sidebar Filter Links Pattern
Always use `array_merge(request()->except([...]), [...])` to preserve other active filters:
```blade
{{ route('student.library.index', array_merge(
    request()->except(['category_id', 'page']),
    ['category_id' => $cat->id, 'view' => request('view', 'grid')]
)) }}
```

### Settings Usage in Views
```blade
{{ school_name() }}               {{-- school name --}}
{{ setting('school_logo') }}      {{-- logo path --}}
{{ setting('school_address') }}   {{-- address --}}
```

### Writing Large Blade Files on Windows/XAMPP
PowerShell heredocs (`@'...'@`) sometimes silently fail for large files.  
**Reliable method:** Write a PHP script to a temp file, execute it, then delete:
```powershell
# write_file.php → file_put_contents('path/to/file.blade.php', $content);
C:\xampp\php\php.exe write_file.php
Remove-Item write_file.php
C:\xampp\php\php.exe artisan view:clear
```

### Artisan Commands (run from project root)
```powershell
C:\xampp\php\php.exe artisan view:clear      # after blade changes
C:\xampp\php\php.exe artisan cache:clear     # after settings/config changes
C:\xampp\php\php.exe artisan migrate         # after new migrations
C:\xampp\php\php.exe artisan route:list      # verify route names
```

---

## 11. Pending / Suggested Next Features

The following areas have not yet been touched and are candidates for the next development session:

| Area | Suggestion |
|---|---|
| **Student Dashboard** | Add quick stats (enrolled courses, quiz scores, recent books), progress bars per course |
| **Student Course Show** | Redesign to match the emerald template style (lesson list with progress indicators) |
| **Admin Dashboard** | Charts (enrollment trends, quiz pass rates), recent activity feed |
| **Notifications** | In-app notifications for new course enrollments, quiz results, certificate issuance |
| **Book Reading** | In-browser PDF viewer (`pdf.js` or iframe) on the `library/show` page |
| **Quiz Timer** | Countdown timer on the quiz take page |
| **Attendance QR** | QR-code-based quick attendance marking |
| **Grade Level badges on Courses** | Student course index page lacks grade/strand filter similar to library |
| **Profile Page Styling** | `profile/edit.blade.php` still uses the default Breeze layout |
| **Seeders** | No demo seeders exist yet — useful for testing |
| **Teacher-specific views** | Teachers share the admin panel; a dedicated teacher dashboard would improve UX |

---

## 12. File Quick Reference

| Purpose | Path |
|---|---|
| Main student layout | `resources/views/layouts/lms.blade.php` |
| Admin layout | `resources/views/layouts/admin.blade.php` |
| Guest/auth layout | `resources/views/layouts/guest.blade.php` |
| Student library index | `resources/views/student/library/index.blade.php` |
| Student library show | `resources/views/student/library/show.blade.php` |
| Admin book create/edit | `resources/views/admin/books/create.blade.php` |
| School settings view | `resources/views/admin/settings/index.blade.php` |
| Global helpers | `app/helpers.php` |
| Setting model | `app/Models/Setting.php` |
| Student BookController | `app/Http/Controllers/Student/BookController.php` |
| Admin BookController | `app/Http/Controllers/Admin/BookController.php` |
| English lang file | `lang/en/messages.php` |
| Arabic lang file | `lang/ar/messages.php` |
| Routes | `routes/web.php` |
