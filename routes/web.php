<?php

use App\Http\Controllers\Admin\PassageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// Admin Controllers
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\AdminSecurityLogController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\ExamScheduleController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\RequestController as AdminRequestController;
use App\Http\Controllers\Admin\StaffRequestController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\LiveMonitorController;
use App\Http\Controllers\Admin\AttemptControlController;

// Student Controllers
use App\Http\Controllers\Student\Auth\LoginController as StudentLoginController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ExamController as StudentExamController;

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

// System Utility Routes
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

Route::view('/', 'welcome')->name('welcome');

// =====================
// SuperAdmin Routes
// =====================
Route::prefix('superadmin')->name('superadmin.')->group(function () {

    // -------- Guest SuperAdmin Routes --------
    Route::middleware('guest:superadmin')->group(function () {
        Route::get('login', [SuperAdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [SuperAdminLoginController::class, 'login'])->name('login.submit');
        Route::get('login-otp', [SuperAdminLoginController::class, 'showOtpForm'])->name('otp.form');
        Route::post('login-otp', [SuperAdminLoginController::class, 'sendOtp'])->name('otp.send');
        Route::get('verify-otp', [SuperAdminLoginController::class, 'showVerifyOtpForm'])->name('otp.verify.form');
        Route::post('verify-otp', [SuperAdminLoginController::class, 'verifyOtp'])->name('otp.verify');
    });

    // -------- Authenticated SuperAdmin Routes --------
    Route::middleware('auth:superadmin')->group(function () {

        // Session
        Route::post('logout', [SuperAdminLoginController::class, 'logout'])->name('logout');
        Route::get('dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');

        // Security Logs
        Route::get('security-logs', [SecurityLogController::class, 'index'])->name('security.logs');
        Route::get('security-logs/export', [SecurityLogController::class, 'export'])->name('security.logs.export');

        // School Management
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

        // Admin Management
        Route::prefix('admins')->name('admins.')->group(function () {
            Route::get('/', [AdminController::class, 'index'])->name('index');
            Route::get('create', [AdminController::class, 'create'])->name('create');
            Route::post('/', [AdminController::class, 'store'])->name('store');
            Route::get('{admin}/edit', [AdminController::class, 'edit'])->name('edit');
            Route::put('{admin}', [AdminController::class, 'update'])->name('update');
        });

        // Staff Requests Management
        Route::prefix('staff-requests')->name('staff-requests.')->group(function () {
            Route::get('/', [SuperAdminStaffRequestController::class, 'index'])->name('index');
            Route::get('{staffRequest}', [SuperAdminStaffRequestController::class, 'show'])->name('show');
            Route::post('{staffRequest}/approve', [SuperAdminStaffRequestController::class, 'approve'])->name('approve');
            Route::post('{staffRequest}/reject', [SuperAdminStaffRequestController::class, 'reject'])->name('reject');
        });

        // Admin Requests
        Route::prefix('admin-requests')->name('admin-requests.')->group(function () {
            Route::get('/', [SuperAdminAdminRequestController::class, 'index'])->name('index');
            Route::post('{adminRequest}/action', [SuperAdminAdminRequestController::class, 'action'])->name('action');
        });

        // Student Management
        Route::prefix('students')->name('students.')->group(function () {
            Route::get('/', [SuperAdminStudentController::class, 'index'])->name('index');
            Route::get('{id}', [SuperAdminStudentController::class, 'show'])->name('show');
            Route::post('{id}/status', [SuperAdminStudentController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('{id}/transfer', [SuperAdminStudentController::class, 'transfer'])->name('transfer');
            Route::post('{id}/reset-exam', [SuperAdminStudentController::class, 'resetExam'])->name('reset-exam');
            Route::post('bulk-action', [SuperAdminStudentController::class, 'bulkAction'])->name('bulk-action');
        });

        // Exam Management
        Route::prefix('exams')->name('exams.')->group(function () {
            Route::get('/', [SuperAdminExamController::class, 'index'])->name('index');
            Route::get('/violation-summary', [SuperAdminExamController::class, 'violationSummary'])->name('violation-summary');
            
            // Live Monitor
            Route::get('/{id}/monitor', [SuperAdminLiveMonitorController::class, 'index'])->name('monitor');
            Route::get('/{id}/monitor/data', [SuperAdminLiveMonitorController::class, 'data'])->name('monitor.data');
            
            // WebRTC & Controls (Nested under attempts for cleaner API, but grouped here for SuperAdmin)
            Route::prefix('../attempts')->group(function() {
                Route::get('{attemptId}/stream', [SuperAdminLiveMonitorController::class, 'stream'])->name('attempts.stream');
                Route::post('{attemptId}/signal', [SuperAdminLiveMonitorController::class, 'sendSignal'])->name('attempts.signal');
                Route::post('{attemptId}/terminate', [SuperAdminAttemptControlController::class, 'terminate'])->name('attempts.terminate');
                Route::post('{attemptId}/extend', [SuperAdminAttemptControlController::class, 'extendTime'])->name('attempts.extend');
            });

            Route::get('/{id}', [SuperAdminExamController::class, 'show'])->name('show');
            Route::post('{id}/force-close', [SuperAdminExamController::class, 'forceClose'])->name('force-close');
        });
    });
});

// =====================
// Admin Routes
// =====================
Route::prefix('admin')->name('admin.')->group(function () {

    // -------- Login & Security --------
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminLoginController::class, 'login'])->name('login.submit');
    Route::post('send-otp', [AdminLoginController::class, 'sendOtp'])->name('send.otp');
    Route::get('verify-otp', [AdminLoginController::class, 'otpForm'])->name('otp.verify.form');
    Route::post('verify-otp', [AdminLoginController::class, 'verifyOtp'])->name('verify.otp');
    Route::get('security-logs', [AdminSecurityLogController::class, 'index'])->name('security.logs');
    Route::get('security-logs/export', [AdminSecurityLogController::class, 'export'])->name('security.logs.export');

    // -------- Authenticated Admin Area --------
    Route::middleware(['auth:admin', \App\Http\Middleware\CheckSchoolActive::class . ':admin'])->group(function () {

        Route::view('dashboard', 'admin.dashboard')->name('dashboard');
        Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

        // Staff Creation Wizard
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

        // Staff Requests
        Route::get('requests/staff/create', [AdminRequestController::class, 'createStaffRequest'])->name('requests.staff.create');
        Route::post('requests/staff', [AdminRequestController::class, 'storeStaffRequest'])->name('requests.staff.store');

        // Student Management
        Route::get('students/bulk-sample', [StudentController::class, 'downloadSample'])->name('students.bulk_sample');
        Route::get('students/bulk-create', [StudentController::class, 'bulkCreate'])->name('students.bulk_create');
        Route::post('students/bulk-store', [StudentController::class, 'bulkStore'])->name('students.bulk_store');
        Route::resource('students', StudentController::class);
    
        Route::get('/admin/exams/{exam}/monitor', 
        [LiveMonitorController::class, 'index']
    )->name('admin.exams.monitor');
    });

    Route::get('questions/bulk-upload', [QuestionController::class, 'bulkForm'])
        ->name('questions.bulk.form');

    Route::get('questions/bulk-sample', [QuestionController::class, 'downloadSample'])
        ->name('questions.bulk.sample');

    Route::post('questions/bulk-upload', [QuestionController::class, 'bulkUpload'])
        ->name('questions.bulk.upload');

    Route::resource('questions', QuestionController::class)
        ->except(['show']);


    // admin routes
    Route::get('passages', [PassageController::class, 'index'])
        ->name('passages.index');

    Route::get('passages/create', [PassageController::class, 'create'])
        ->name('passages.create');

    Route::post('passages', [PassageController::class, 'store'])
        ->name('passages.store');


    Route::get('exams', [ExamController::class, 'index'])->name('exams.index');
    Route::get('exams/create', [ExamController::class, 'create'])->name('exams.create');
    Route::post('exams', [ExamController::class, 'store'])->name('exams.store');

    Route::get('exams/{id}/questions', [ExamController::class, 'questions'])
        ->name('exams.questions');

    Route::post('exams/{id}/questions', [ExamController::class, 'attachQuestions'])
        ->name('exams.attach');

    Route::get('exams/{id}/schedule', [ExamScheduleController::class, 'create'])
        ->name('exams.schedule');

    Route::post('exams/{id}/schedule', [ExamScheduleController::class, 'store'])
        ->name('exams.schedule.store');

    Route::post('exams/{id}/publish', [ExamController::class, 'publish'])
        ->name('exams.publish');

    Route::post('exams/{id}/close', [ExamController::class, 'close'])
        ->name('exams.close');
    Route::get('exams/{id}', [ExamController::class, 'show'])
        ->name('exams.show');
    Route::get('exams/{id}/edit', [ExamController::class, 'edit'])
        ->name('exams.edit');

    Route::put('exams/{id}', [ExamController::class, 'update'])
        ->name('exams.update');
    Route::delete('exams/{id}', [ExamController::class, 'destroy'])
        ->name('exams.destroy');

        Route::get('practice-exams', [ExamController::class, 'practice'])
    ->name('exams.practice');

Route::get('practice-exams/{exam}/solution', [ExamController::class, 'solution'])
    ->name('exams.solution');
    Route::get('practice-solutions', [ExamController::class, 'practiceSolutions'])
    ->name('exams.practice.solutions');


    // Live Monitoring & Control Room
    Route::get('exams/{id}/monitor', [LiveMonitorController::class, 'index'])->name('exams.monitor');
    Route::get('exams/{id}/monitor/data', [LiveMonitorController::class, 'data'])->name('exams.monitor.data');
    
    // WebRTC Signaling (Admin Side)
    Route::get('attempts/{attemptId}/stream', [LiveMonitorController::class, 'stream'])->name('attempts.stream');
    Route::post('attempts/{attemptId}/signal', [LiveMonitorController::class, 'sendSignal'])->name('attempts.signal');

    // Attempt Controls
    Route::post('attempts/{attemptId}/terminate', [AttemptControlController::class, 'terminate'])->name('attempts.terminate');
    Route::post('attempts/{attemptId}/extend', [AttemptControlController::class, 'extendTime'])->name('attempts.extend');
});

// =====================
// Student Routes
// =====================
Route::prefix('student')->name('student.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('login', [StudentLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [StudentLoginController::class, 'login'])->name('login.submit');
    });

    Route::middleware(['auth', \App\Http\Middleware\CheckSchoolActive::class . ':web'])->group(function () {
        Route::get('dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        Route::get('exams', [StudentExamController::class, 'index'])->name('exams.index');
        Route::get('exams/history', [StudentExamController::class, 'history'])->name('exams.history');
        Route::get('exams/{id}/live', [StudentExamController::class, 'live'])->name('exams.live');
        Route::post('exams/{id}/submit', [StudentExamController::class, 'submit'])->name('exams.submit');
        Route::post('exams/{id}/violation', [StudentExamController::class, 'logViolation'])->name('exams.violation');
        
        // Heartbeat & Signaling
        Route::post('exams/{id}/heartbeat', [StudentExamController::class, 'heartbeat'])->name('exams.heartbeat');
        Route::post('exams/{id}/signal', [StudentExamController::class, 'signal'])->name('exams.signal');

        Route::post('logout', [StudentLoginController::class, 'logout'])->name('logout');
        Route::view('profile', 'student.profile')->name('profile');
    });
});
