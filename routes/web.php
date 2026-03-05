<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\TeacherMaterialController;
use App\Http\Controllers\Student;
use Illuminate\Support\Facades\Route;

/* ---------- Public ---------- */
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

/* ---------- Auth / Profile (Breeze) ---------- */
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->hasRole('admin')) return redirect()->route('admin.dashboard');
    if ($user->hasRole('teacher')) return redirect()->route('admin.dashboard');
    return redirect()->route('student.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* ---------- Admin / Teacher routes ---------- */
Route::middleware(['auth', 'verified', 'role:admin|teacher'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Users (admin only)
        Route::middleware('role:admin')->group(function () {
            Route::resource('users', Admin\UserController::class);
        });

        // Categories
        Route::resource('categories', Admin\CategoryController::class);

        // Settings (admin only)
        Route::middleware('role:admin')->group(function () {
            Route::get('settings', [Admin\SettingsController::class, 'index'])->name('settings.index');
            Route::put('settings', [Admin\SettingsController::class, 'update'])->name('settings.update');
        });

        // Courses & Lessons
        Route::resource('courses', Admin\CourseController::class);
        Route::resource('courses.lessons', Admin\LessonController::class);

        // Enrollments
        Route::resource('enrollments', Admin\EnrollmentController::class)->only(['index', 'create', 'store', 'update', 'destroy']);
        Route::get('courses/{course}/lessons-json', [Admin\EnrollmentController::class, 'lessons'])->name('courses.lessons-json');
        Route::get('courses/{course}/enrollments-json', [Admin\EnrollmentController::class, 'enrolledStudents'])->name('courses.enrollments-json');

        // E-Library (Students)
        Route::resource('books', Admin\BookController::class);

        // Teacher Resource Library (admin + teacher: all except delete)
        Route::resource('teacher-materials', Admin\TeacherMaterialController::class)
            ->parameters(['teacher-materials' => 'teacherMaterial'])
            ->except(['destroy']);
        Route::get('teacher-materials/{teacherMaterial}/download', [Admin\TeacherMaterialController::class, 'download'])->name('teacher-materials.download');

        // Teacher Resource Library (admin only: delete)
        Route::middleware('role:admin')->group(function () {
            Route::delete('teacher-materials/{teacherMaterial}', [Admin\TeacherMaterialController::class, 'destroy'])->name('teacher-materials.destroy');
        });

        // Teacher Collections
        Route::resource('teacher-collections', Admin\TeacherCollectionController::class)
            ->parameters(['teacher-collections' => 'teacherCollection']);
        Route::post('teacher-collections/{teacherCollection}/materials', [Admin\TeacherCollectionController::class, 'addMaterials'])->name('teacher-collections.add-materials');
        Route::delete('teacher-collections/{teacherCollection}/materials/{teacherMaterial}', [Admin\TeacherCollectionController::class, 'removeMaterial'])->name('teacher-collections.remove-material');
        Route::post('teacher-collections/{teacherCollection}/reorder-materials', [Admin\TeacherCollectionController::class, 'reorderMaterials'])->name('teacher-collections.reorder-materials');
        Route::post('teacher-collections/reorder', [Admin\TeacherCollectionController::class, 'reorder'])->name('teacher-collections.reorder');
        Route::patch('teacher-collections/{teacherCollection}/materials/{teacherMaterial}/move', [Admin\TeacherCollectionController::class, 'moveMaterial'])->name('teacher-collections.move-material');

        // Quizzes
        Route::resource('quizzes', Admin\QuizController::class);
        Route::resource('quizzes.questions', Admin\QuizQuestionController::class);

        // Attendance
        Route::resource('attendance', Admin\AttendanceController::class);
        Route::get('attendance/course/{course}', [Admin\AttendanceController::class, 'byCourse'])->name('attendance.by-course');

        // Certificates
        Route::resource('certificates', Admin\CertificateController::class);
        Route::post('certificates/{user}/{course}/generate', [Admin\CertificateController::class, 'generate'])->name('certificates.generate');
        Route::get('certificates/{certificate}/download', [Admin\CertificateController::class, 'download'])->name('certificates.download');
    });

/* ---------- Student routes ---------- */
Route::middleware(['auth', 'verified', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        Route::get('/dashboard', [Student\DashboardController::class, 'index'])->name('dashboard');

        // My Courses
        Route::get('/courses', [Student\CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/{course}', [Student\CourseController::class, 'show'])->name('courses.show');
        Route::get('/courses/{course}/lessons/{lesson}', [Student\CourseController::class, 'lesson'])->name('courses.lesson');
        Route::get('/courses/{course}/lessons/{lesson}/download', [Student\CourseController::class, 'downloadAttachment'])->name('courses.lessons.download');

        // E-Library
        Route::get('/library', [Student\BookController::class, 'index'])->name('library.index');
        Route::get('/library/{book}', [Student\BookController::class, 'show'])->name('library.show');
        Route::get('/library/{book}/download', [Student\BookController::class, 'download'])->name('library.download');

        // Quizzes
        Route::get('/quizzes/{quiz}', [Student\QuizController::class, 'show'])->name('quizzes.show');
        Route::post('/quizzes/{quiz}/start', [Student\QuizController::class, 'start'])->name('quizzes.start');
        Route::post('/quizzes/{quiz}/submit', [Student\QuizController::class, 'submit'])->name('quizzes.submit');
        Route::get('/quizzes/{quiz}/result/{attempt}', [Student\QuizController::class, 'result'])->name('quizzes.result');

        // Certificates
        Route::get('/certificates', [Student\CertificateController::class, 'index'])->name('certificates.index');
        Route::get('/certificates/{certificate}/download', [Student\CertificateController::class, 'download'])->name('certificates.download');
    });

require __DIR__.'/auth.php';
