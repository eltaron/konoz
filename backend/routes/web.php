<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;

// ========== Auth Routes ==========
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// ========== Public Pages ==========
Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/blog', [FrontendController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [FrontendController::class, 'blogPost'])->name('blog-post');
Route::get('/departments', [FrontendController::class, 'departments'])->name('departments');
Route::get('/course-details', [FrontendController::class, 'courseDetails'])->name('course-details');
Route::get('/terms', [FrontendController::class, 'terms'])->name('terms');
Route::get('/privacy', [FrontendController::class, 'privacy'])->name('privacy');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/honor-board', [FrontendController::class, 'honorBoard'])->name('honor-board');
Route::get('/educational-support', [FrontendController::class, 'educationalSupport'])->name('educational-support');
Route::get('/parenting-support', [FrontendController::class, 'parentingSupport'])->name('parenting-support');
Route::get('/coding', [FrontendController::class, 'coding'])->name('coding');
Route::get('/reviews', [FrontendController::class, 'reviews'])->name('reviews');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/contact', [FrontendController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/certificates', [FrontendController::class, 'certificates'])->name('certificates');
Route::get('/verify/{code}', [FrontendController::class, 'verifyCertificate'])->name('certificate.verify');
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'en'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('lang.switch');

Route::post('/enroll', [FrontendController::class, 'enroll'])->name('enroll');
Route::get('/enroll/courses', [FrontendController::class, 'enrollCourses'])->name('enroll.courses');

// ========== Teacher Dashboard ==========
Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'role:admin,teacher'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\TeacherController::class, 'dashboard'])->name('dashboard');
    Route::get('/courses', [\App\Http\Controllers\TeacherController::class, 'courses'])->name('courses');
    Route::get('/students', [\App\Http\Controllers\TeacherController::class, 'students'])->name('students');
    Route::post('/students/enroll', [\App\Http\Controllers\TeacherController::class, 'storeEnrollStudent'])->name('students.enroll');
    Route::get('/sessions', [\App\Http\Controllers\TeacherController::class, 'sessions'])->name('sessions');
    Route::get('/schedule', [\App\Http\Controllers\TeacherController::class, 'schedule'])->name('schedule');
    Route::get('/messages', [\App\Http\Controllers\TeacherController::class, 'messages'])->name('messages');
    Route::get('/exams', [\App\Http\Controllers\TeacherController::class, 'exams'])->name('exams');
    Route::get('/certificates', [\App\Http\Controllers\TeacherController::class, 'certificates'])->name('certificates');
    Route::get('/reports', [\App\Http\Controllers\TeacherController::class, 'reports'])->name('reports');
    Route::get('/reports/print', [\App\Http\Controllers\TeacherController::class, 'reportsPrint'])->name('reports.print');
    Route::get('/settings', [\App\Http\Controllers\TeacherController::class, 'settings'])->name('settings');
    Route::post('/settings/update', [\App\Http\Controllers\TeacherController::class, 'updateSettings'])->name('settings.update');
    Route::get('/notifications', [\App\Http\Controllers\TeacherController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\TeacherController::class, 'markAllNotificationsRead'])->name('notifications.mark-all-read');
    Route::get('/student-profile/{id?}', [\App\Http\Controllers\TeacherController::class, 'studentProfile'])->name('student-profile');
    Route::get('/exam-details/{id?}', [\App\Http\Controllers\TeacherController::class, 'examDetails'])->name('exam-details');
    Route::get('/exam-results/{id?}', [\App\Http\Controllers\TeacherController::class, 'examResults'])->name('exam-results');
    Route::get('/exam-results/{id}/print', [\App\Http\Controllers\TeacherController::class, 'examResultsPrint'])->name('exam-results.print');
    Route::get('/course-details/{id?}', [\App\Http\Controllers\TeacherController::class, 'courseDetails'])->name('course-details');
    Route::patch('/student/{student}/juz/{juz}', [\App\Http\Controllers\TeacherController::class, 'juzUpdate'])->name('juz-update');

    // Teacher CRUD operations
    Route::post('/courses/store', [\App\Http\Controllers\TeacherController::class, 'storeCourse'])->name('courses.store');
    Route::post('/courses/{course}/update', [\App\Http\Controllers\TeacherController::class, 'updateCourse'])->name('courses.update');
    Route::delete('/courses/{course}', [\App\Http\Controllers\TeacherController::class, 'deleteCourse'])->name('courses.delete');
    Route::delete('/courses/{course}/students/{student}', [\App\Http\Controllers\TeacherController::class, 'detachStudent'])->name('courses.students.detach');

    Route::post('/sessions/store', [\App\Http\Controllers\TeacherController::class, 'storeSession'])->name('sessions.store');
    Route::post('/certificates/store', [\App\Http\Controllers\TeacherController::class, 'storeCertificate'])->name('certificates.store');
    Route::patch('/exams/{exam}/toggle-status', [\App\Http\Controllers\TeacherController::class, 'toggleExamStatus'])->name('exams.toggle-status');
    Route::post('/sessions/{session}/update', [\App\Http\Controllers\TeacherController::class, 'updateSession'])->name('sessions.update');
    Route::delete('/sessions/{session}', [\App\Http\Controllers\TeacherController::class, 'deleteSession'])->name('sessions.delete');

    Route::post('/lessons/store', [\App\Http\Controllers\TeacherController::class, 'storeLesson'])->name('lessons.store');
    Route::post('/lessons/{lesson}/update', [\App\Http\Controllers\TeacherController::class, 'updateLesson'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [\App\Http\Controllers\TeacherController::class, 'deleteLesson'])->name('lessons.delete');

    Route::post('/exams/store', [\App\Http\Controllers\TeacherController::class, 'storeExam'])->name('exams.store');
    Route::post('/exams/{exam}/update', [\App\Http\Controllers\TeacherController::class, 'updateExam'])->name('exams.update');
    Route::delete('/exams/{exam}', [\App\Http\Controllers\TeacherController::class, 'deleteExam'])->name('exams.delete');
    Route::post('/questions/store', [\App\Http\Controllers\TeacherController::class, 'storeQuestion'])->name('questions.store');

    Route::get('/messages/fetch/{contactId}', [\App\Http\Controllers\TeacherController::class, 'fetchMessages'])->name('messages.fetch');
    Route::post('/messages/send', [\App\Http\Controllers\TeacherController::class, 'sendMessage'])->name('messages.send');
});

// ========== Admin Reports Print ==========
Route::prefix('admin-reports')->name('admin.reports.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/print', [\App\Http\Controllers\AdminReportController::class, 'print'])->name('print');
});

// ========== Student Dashboard ==========
Route::prefix('student')->name('student.')->middleware(['auth', 'role:admin,student'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/hifdh-journey', [\App\Http\Controllers\StudentController::class, 'hifdhJourney'])->name('hifdh-journey');
    Route::get('/certificates', [\App\Http\Controllers\StudentController::class, 'certificates'])->name('certificates');
    Route::get('/exams', [\App\Http\Controllers\StudentController::class, 'exams'])->name('exams');
    Route::get('/settings', [\App\Http\Controllers\StudentController::class, 'settings'])->name('settings');
    Route::post('/settings/update', [\App\Http\Controllers\StudentController::class, 'updateSettings'])->name('settings.update');
    Route::get('/messages', [\App\Http\Controllers\StudentController::class, 'messages'])->name('messages');
    Route::get('/messages/fetch/{contactId}', [\App\Http\Controllers\StudentController::class, 'fetchMessages'])->name('messages.fetch');
    Route::post('/messages/send', [\App\Http\Controllers\StudentController::class, 'sendMessage'])->name('messages.send');
    Route::get('/schedule', [\App\Http\Controllers\StudentController::class, 'schedule'])->name('schedule');
    Route::get('/treasure-map', [\App\Http\Controllers\StudentController::class, 'treasureMap'])->name('treasure-map');
    Route::post('/activity/log', [\App\Http\Controllers\StudentController::class, 'logActivity'])->name('activity.log');
    Route::post('/activity/delete', [\App\Http\Controllers\StudentController::class, 'deleteActivity'])->name('activity.delete');
    Route::get('/live', [\App\Http\Controllers\StudentController::class, 'live'])->name('live');
    Route::post('/live/attendance', [\App\Http\Controllers\StudentController::class, 'markAttendance'])->name('live.attendance');
    Route::post('/live/quiz', [\App\Http\Controllers\StudentController::class, 'saveQuizResult'])->name('live.quiz');
    Route::get('/courses', [\App\Http\Controllers\StudentController::class, 'courses'])->name('courses');
    Route::post('/courses/enroll/{course}', [\App\Http\Controllers\StudentController::class, 'enrollRequest'])->name('courses.enroll');
    Route::get('/my-courses', [\App\Http\Controllers\StudentController::class, 'myCourses'])->name('my-courses');
    Route::get('/course/{course}', [\App\Http\Controllers\StudentController::class, 'courseDetail'])->name('course-detail');
    Route::get('/lesson/{lesson}', [\App\Http\Controllers\StudentController::class, 'lessonShow'])->name('lesson.show');
    Route::get('/exam/{exam}', [\App\Http\Controllers\StudentController::class, 'exam'])->name('exam.show');
    Route::post('/exam/{exam}/submit', [\App\Http\Controllers\StudentController::class, 'submitExam'])->name('exam.submit');
    Route::get('/certificate/{certificate}', [\App\Http\Controllers\StudentController::class, 'certificateShow'])->name('certificate.show');
    Route::post('/juz/{juz}/update', [\App\Http\Controllers\StudentController::class, 'juzUpdate'])->name('juz.update');
    Route::post('/notifications/read-all', [\App\Http\Controllers\StudentController::class, 'markNotificationsRead'])->name('notifications.read-all');
});
