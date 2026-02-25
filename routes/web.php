<?php

use App\Http\Controllers\Admin\AdminResultController;
use App\Http\Controllers\Admin\AttemptControlController;
use App\Http\Controllers\Admin\PassageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// System, Utility, General
use App\Http\Controllers\DemoRequestController;

// Admin Controllers
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminSecurityLogController;
use App\Http\Controllers\Admin\ExamController as AdminExamController;
use App\Http\Controllers\Admin\ExamScheduleController as AdminExamScheduleController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\RequestController as AdminRequestController;
use App\Http\Controllers\Admin\StaffRequestController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\LiveMonitorController as AdminLiveMonitorController;
use App\Http\Controllers\Admin\AttemptControlController as AdminAttemptControlController;
use App\Http\Controllers\Admin\ReportController;

// Student Controllers
use App\Http\Controllers\Student\Auth\LoginController as StudentLoginController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ExamController as StudentExamController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;

// SuperAdmin Controllers
use App\Http\Controllers\SuperAdmin\AdminController;
use App\Http\Controllers\SuperAdmin\AdminRequestController as SuperAdminAdminRequestController;
use App\Http\Controllers\SuperAdmin\Auth\LoginController as SuperAdminLoginController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\SchoolController;
use App\Http\Controllers\SuperAdmin\SecurityLogController;
use App\Http\Controllers\SuperAdmin\StaffRequestController as SuperAdminStaffRequestController;
use App\Http\Controllers\SuperAdmin\StudentController as SuperAdminStudentController;
use App\Http\Controllers\SuperAdmin\ExamController as SuperAdminExamController;
use App\Http\Controllers\SuperAdmin\LiveMonitorController as SuperAdminLiveMonitorController;
use App\Http\Controllers\SuperAdmin\AttemptControlController as SuperAdminAttemptControlController;

// =========================
// System Utility Routes
// =========================
Route::get('/clear-cache', function () {
    if (!app()->environment(['local', 'staging', 'production'])) {
        abort(403, 'Unauthorized.');
    }
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return "All caches cleared successfully!";
})->name('clear.cache');

// for storage link
Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'storage:link executed successfully';
});

Route::view('/', 'welcome')->name('welcome');

Route::view('/e-leaning', 'e-leaning')->name('e-leaning');

Route::post('/request-demo', [DemoRequestController::class, 'store'])->name('request.demo');


// SuperAdmin Routes
Route::prefix('superadmin')->name('superadmin.')->group(function () {

    // -- Guest SuperAdmin (Authentication) --
    Route::middleware('guest:superadmin')->group(function () {
        Route::get('login', [SuperAdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [SuperAdminLoginController::class, 'login'])->name('login.submit');
        Route::get('login-otp', [SuperAdminLoginController::class, 'showOtpForm'])->name('otp.form');
        Route::post('login-otp', [SuperAdminLoginController::class, 'sendOtp'])->name('otp.send');
        Route::get('verify-otp', [SuperAdminLoginController::class, 'showVerifyOtpForm'])->name('otp.verify.form');
        Route::post('verify-otp', [SuperAdminLoginController::class, 'verifyOtp'])->name('otp.verify');
    });

    // -- Authenticated SuperAdmin Area --
    Route::middleware('auth:superadmin')->group(function () {

        // ---- SuperAdmin Session & Dashboard ----
        Route::post('logout', [SuperAdminLoginController::class, 'logout'])->name('logout');
        Route::get('dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');

        // ---- Security Logs ----
        Route::get('security-logs', [SecurityLogController::class, 'index'])->name('security.logs');
        Route::get('security-logs/export', [SecurityLogController::class, 'export'])->name('security.logs.export');

        // ---- School Management ----
        Route::prefix('schools')->name('schools.')->group(function () {
            Route::get('/', [SchoolController::class, 'index'])->name('index');
            Route::get('suspension', [SchoolController::class, 'suspension'])->name('suspension');
            Route::post('{school}/toggle-suspension', [SchoolController::class, 'toggleSuspension'])->name('toggle-suspension');
            Route::get('create', [SchoolController::class, 'create'])->name('create');
            Route::post('store', [SchoolController::class, 'store'])->name('store');
            Route::get('{school}/create-admin', [SchoolController::class, 'createAdmin'])->name('create-admin');
            Route::post('{school}/admin-store', [AdminController::class, 'storeSchoolAdmin'])->name('admin-store');
            Route::get('{school}/edit', [SchoolController::class, 'edit'])->name('edit');
            Route::put('{school}', [SchoolController::class, 'update'])->name('update');
            Route::get('{school}/edit-admin', [SchoolController::class, 'editAdmin'])->name('edit-admin');
            Route::put('{school}/admin-update/{admin}', [AdminController::class, 'updateSchoolAdmin'])->name('admin-update');
        });

        // ---- Admin Management ----
        Route::prefix('admins')->name('admins.')->group(function () {
            Route::get('/', [AdminController::class, 'index'])->name('index');
            Route::get('create', [AdminController::class, 'create'])->name('create');
            Route::post('/', [AdminController::class, 'store'])->name('store');
            Route::get('{admin}/edit', [AdminController::class, 'edit'])->name('edit');
            Route::put('{admin}', [AdminController::class, 'update'])->name('update');
        });

        // ---- Staff Requests Management ----
        Route::prefix('staff-requests')->name('staff-requests.')->group(function () {
            Route::get('/', [SuperAdminStaffRequestController::class, 'index'])->name('index');
            Route::get('{staffRequest}', [SuperAdminStaffRequestController::class, 'show'])->name('show');
            Route::post('{staffRequest}/approve', [SuperAdminStaffRequestController::class, 'approve'])->name('approve');
            Route::post('{staffRequest}/reject', [SuperAdminStaffRequestController::class, 'reject'])->name('reject');
        });

        // ---- Admin Requests ----
        Route::prefix('admin-requests')->name('admin-requests.')->group(function () {
            Route::get('/', [SuperAdminAdminRequestController::class, 'index'])->name('index');
            Route::post('{adminRequest}/action', [SuperAdminAdminRequestController::class, 'action'])->name('action');
        });

        // ---- Student Management ----
        Route::prefix('students')->name('students.')->group(function () {
            Route::get('/', [SuperAdminStudentController::class, 'index'])->name('index');
            Route::get('{id}', [SuperAdminStudentController::class, 'show'])->name('show');
            Route::post('{id}/status', [SuperAdminStudentController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('{id}/transfer', [SuperAdminStudentController::class, 'transfer'])->name('transfer');
            Route::post('{id}/reset-exam', [SuperAdminStudentController::class, 'resetExam'])->name('reset-exam');
            Route::post('bulk-action', [SuperAdminStudentController::class, 'bulkAction'])->name('bulk-action');
        });

        // ---- Exam Management ----
        Route::prefix('exams')->name('exams.')->group(function () {
            Route::get('/', [SuperAdminExamController::class, 'index'])->name('index');
            Route::get('/violation-summary', [SuperAdminExamController::class, 'violationSummary'])->name('violation-summary');
            // Live Monitor
            Route::get('/{id}/monitor', [SuperAdminLiveMonitorController::class, 'index'])->name('monitor');
            Route::get('/{id}/monitor/data', [SuperAdminLiveMonitorController::class, 'data'])->name('monitor.data');
            Route::get('/{id}', [SuperAdminExamController::class, 'show'])->name('show');
            Route::post('{id}/force-close', [SuperAdminExamController::class, 'forceClose'])->name('force-close');
        });

        // ---- WebRTC & Controls (Attempts) ----
        Route::prefix('attempts')->name('attempts.')->group(function () {
            Route::post('{attemptId}/request-stream', [SuperAdminLiveMonitorController::class, 'requestStream'])->name('request_stream');
            Route::post('{attemptId}/terminate', [SuperAdminAttemptControlController::class, 'terminate'])->name('terminate');
            Route::post('{attemptId}/extend', [SuperAdminAttemptControlController::class, 'extendTime'])->name('extend');
        });

        // ---- WebRTC Signaling Streams ----
        Route::prefix('stream')->name('stream.')->group(function () {
            Route::get('{streamId}/poll', [SuperAdminLiveMonitorController::class, 'pollViewer'])->name('poll');
            Route::post('{streamId}/signal', [SuperAdminLiveMonitorController::class, 'viewerSignal'])->name('signal');
        });
    });
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {

    // -- Authentication & Security Logs --
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminLoginController::class, 'login'])->name('login.submit');
    Route::post('send-otp', [AdminLoginController::class, 'sendOtp'])->name('send.otp');
    Route::get('verify-otp', [AdminLoginController::class, 'otpForm'])->name('otp.verify.form');
    Route::post('verify-otp', [AdminLoginController::class, 'verifyOtp'])->name('verify.otp');
    Route::get('security-logs', [AdminSecurityLogController::class, 'index'])->name('security.logs');
    Route::get('security-logs/export', [AdminSecurityLogController::class, 'export'])->name('security.logs.export');

    // -- Authenticated Admin Area --
    Route::middleware(['auth:admin', \App\Http\Middleware\CheckSchoolActive::class . ':admin'])->group(function () {

        // --- Admin Dashboard & Session ---
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

        // --- Staff Creation Wizard ---
        Route::prefix('staff/create')->name('staff.create.')->group(function () {
            Route::get('step-1', [StaffRequestController::class, 'step1'])->name('step1');
            Route::post('step-1', [StaffRequestController::class, 'postStep1'])->name('postStep1');
            Route::get('step-2', [StaffRequestController::class, 'step2'])->name('step2');
            Route::post('step-2', [StaffRequestController::class, 'postStep2'])->name('postStep2');
            Route::get('step-3', [StaffRequestController::class, 'step3'])->name('step3');
            Route::post('step-3', [StaffRequestController::class, 'postStep3'])->name('postStep3');
            Route::get('review', [StaffRequestController::class, 'review'])->name('review');
            Route::post('submit', [StaffRequestController::class, 'submit'])->name('submit');
        });

        // --- Staff Requests ---
        Route::get('requests/staff/create', [AdminRequestController::class, 'createStaffRequest'])->name('requests.staff.create');
        Route::post('requests/staff', [AdminRequestController::class, 'storeStaffRequest'])->name('requests.staff.store');

        // --- Student Management ---
        Route::get('students/bulk-sample', [AdminStudentController::class, 'downloadSample'])->name('students.bulk_sample');
        Route::get('students/bulk-create', [AdminStudentController::class, 'bulkCreate'])->name('students.bulk_create');
        Route::post('students/bulk-store', [AdminStudentController::class, 'bulkStore'])->name('students.bulk_store');
        Route::get('students/batch-assignment', [AdminStudentController::class, 'batchAssign'])->name('students.batch.assign');
        Route::post('students/batch-assignment', [AdminStudentController::class, 'batchUpdate'])->name('students.batch.update');
        Route::resource('students', AdminStudentController::class);
    });

    // -- Questions Management --
    Route::get('questions/bulk-upload', [QuestionController::class, 'bulkForm'])->name('questions.bulk.form');
    Route::get('questions/bulk-sample', [QuestionController::class, 'downloadSample'])->name('questions.bulk.sample');
    Route::post('questions/bulk-upload', [QuestionController::class, 'bulkUpload'])->name('questions.bulk.upload');
    Route::resource('questions', QuestionController::class)->except(['show']);

    // -- Passages Management --
    Route::get('passages', [PassageController::class, 'index'])->name('passages.index');
    Route::get('passages/create', [PassageController::class, 'create'])->name('passages.create');
    Route::post('passages', [PassageController::class, 'store'])->name('passages.store');

    // -- Exams Management --
    Route::get('exams', [AdminExamController::class, 'index'])->name('exams.index');
    Route::get('exams/create', [AdminExamController::class, 'create'])->name('exams.create');
    Route::post('exams', [AdminExamController::class, 'store'])->name('exams.store');
    Route::get('exams/{id}/questions', [AdminExamController::class, 'questions'])->name('exams.questions');
    Route::post('exams/{id}/questions', [AdminExamController::class, 'attachQuestions'])->name('exams.attach');
    Route::get('exams/{id}/schedule', [AdminExamScheduleController::class, 'create'])->name('exams.schedule');
    Route::post('exams/{id}/schedule', [AdminExamScheduleController::class, 'store'])->name('exams.schedule.store');
    Route::post('exams/{id}/publish', [AdminExamController::class, 'publish'])->name('exams.publish');
    Route::post('exams/{id}/close', [AdminExamController::class, 'close'])->name('exams.close');
    Route::get('exams/{id}', [AdminExamController::class, 'show'])->name('exams.show');
    Route::get('exams/{id}/edit', [AdminExamController::class, 'edit'])->name('exams.edit');
    Route::put('exams/{id}', [AdminExamController::class, 'update'])->name('exams.update');
    Route::delete('exams/{id}', [AdminExamController::class, 'destroy'])->name('exams.destroy');
    Route::get('practice-exams', [AdminExamController::class, 'practice'])->name('exams.practice');
    Route::get('practice-exams/{exam}/solution', [AdminExamController::class, 'solution'])->name('exams.solution');
    Route::get('practice-solutions', [AdminExamController::class, 'practiceSolutions'])->name('exams.practice.solutions');

    // -- Live Monitoring & Control Room --
    Route::get('exams/{id}/monitor', [AdminLiveMonitorController::class, 'index'])->name('exams.monitor');
    Route::get('exams/{id}/monitor/data', [AdminLiveMonitorController::class, 'data'])->name('exams.monitor.data');

    // -- WebRTC Signaling (Admin Side) --
    Route::post('attempts/{attemptId}/request-stream', [AdminLiveMonitorController::class, 'requestStream'])->name('attempts.request_stream');
    Route::prefix('stream')->name('stream.')->group(function () {
        Route::get('{streamId}/poll', [AdminLiveMonitorController::class, 'pollViewer'])->name('poll');
        Route::post('{streamId}/signal', [AdminLiveMonitorController::class, 'viewerSignal'])->name('signal');
    });

    // Attempt Controls
    Route::post('attempts/{attemptId}/terminate', [AttemptControlController::class, 'terminate'])->name('attempts.terminate');
    Route::post('attempts/{attemptId}/extend', [AttemptControlController::class, 'extendTime'])->name('attempts.extend');

    Route::get('results/pending', [AdminResultController::class, 'pending'])
        ->name('results.pending');

    Route::post('results/{attempt}/approve', [AdminResultController::class, 'approve'])
        ->name('results.approve');

    Route::post('results/{attempt}/reject', [AdminResultController::class, 'reject'])
        ->name('results.reject');
    Route::get('results/list', [AdminResultController::class, 'list'])
        ->name('results.list');

    // -- Attempt Controls --
    Route::post('attempts/{attemptId}/terminate', [AdminAttemptControlController::class, 'terminate'])->name('attempts.terminate');
    Route::post('attempts/{attemptId}/extend', [AdminAttemptControlController::class, 'extendTime'])->name('attempts.extend');

    // -- Reports --
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('exams', [ReportController::class, 'exams'])->name('exams');
        Route::get('exams/{id}', [ReportController::class, 'examDetail'])->name('exams.detail');
        Route::get('exams/{id}/export', [ReportController::class, 'exportExamDetail'])->name('exams.detail.export');
        Route::get('exams/{id}/violations', [ReportController::class, 'examViolations'])->name('exams.violations');
        Route::get('exams/{id}/violations/export', [ReportController::class, 'exportExamViolations'])->name('exams.violations.export');
        Route::get('analytics', [ReportController::class, 'analytics'])->name('analytics');
    });
});

// Student Routes
Route::prefix('student')->name('student.')->group(function () {

    // -- Guest (Login) --
    Route::middleware('guest')->group(function () {
        Route::get('login', [StudentLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [StudentLoginController::class, 'login'])->name('login.submit');
    });

    // -- Authenticated Student Area --
    Route::middleware(['auth', \App\Http\Middleware\CheckSchoolActive::class . ':web'])->group(function () {
        Route::get('dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        Route::get('result/{attempt}', [StudentExamController::class, 'result'])
            ->name('result');
        Route::get('results', [StudentExamController::class, 'results'])
            ->name('results.index');
        Route::get('system-check', [StudentDashboardController::class, 'systemCheck'])->name('system.check');
        Route::get('exams', [StudentExamController::class, 'index'])->name('exams.index');
        Route::get('exams/mock', [StudentExamController::class, 'mock'])->name('exams.mock');
        Route::get('exams/history', [StudentExamController::class, 'history'])->name('exams.history');
        Route::get('exams/{id}/live', [StudentExamController::class, 'live'])->name('exams.live');

        // --- Dashboard & Info ---
        Route::get('dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        Route::get('system-check', [StudentDashboardController::class, 'systemCheck'])->name('system.check');

        // --- Exam Listing & Attempting ---
        Route::get('exams', [StudentExamController::class, 'index'])->name('exams.index');
        Route::get('exams/mock', [StudentExamController::class, 'mock'])->name('exams.mock');
        Route::get('exams/history', [StudentExamController::class, 'history'])->name('exams.history');
        Route::get('exams/{id}/live', [StudentExamController::class, 'live'])->name('exams.live');
        Route::post('exams/{id}/submit', [StudentExamController::class, 'submit'])->name('exams.submit');
        Route::post('exams/{id}/violation', [StudentExamController::class, 'logViolation'])->name('exams.violation');

        // --- Heartbeat & Signaling ---
        Route::post('exams/{id}/heartbeat', [StudentExamController::class, 'heartbeat'])->name('exams.heartbeat');
        Route::post('exams/{id}/signal', [StudentExamController::class, 'signal'])->name('exams.signal');
        Route::get('exams/{id}/poll-signals', [StudentExamController::class, 'pollSignals'])->name('exams.pollSignals');

        // --- Session/Logout/Profile ---
        Route::post('logout', [StudentLoginController::class, 'logout'])->name('logout');
        Route::view('profile', 'student.profile')->name('profile');
        Route::put('profile/password', [StudentProfileController::class, 'updatePassword'])->name('password.update');
    });

    Route::get( 'marksheet/download/{attemptId}', [StudentExamController::class,'downloadMarksheet'])->name('marksheet.download');
});
