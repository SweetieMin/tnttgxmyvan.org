<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Back\AttendanceController;
use App\Http\Controllers\Back\DashboardController;
use App\Http\Controllers\Back\ManagementController;
use App\Http\Controllers\Back\PersonnelController;
use App\Http\Controllers\Back\SupportController;
use App\Http\Controllers\Front\ProfileController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/generate-sitemap', [SitemapController::class, 'generate']);

Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile/{token}', 'profileView')->name('profile_view');
});

//Route test
Route::view('/example-page', 'example-page');
Route::view('/example-auth', 'example-auth');

/* Admin route */
Route::prefix('admin')->name('admin.')->group(function () {

    // User authentication routes
    Route::middleware(['guest', 'preventBackHistory'])->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::get('/login', 'loginForm')->name('login');
            Route::get('/forgot-password', 'forgotForm')->name('forgot');
            Route::get('/password/reset/{token}', 'resetForm')->name('reset_password_form');
            Route::post('/reset-password', 'resetPassword')->name('reset_password');
            Route::get('/verify-email/{token}', 'verifyEmail')->name('verify_email')->withoutMiddleware('guest');
        });
    });

    Route::middleware(['auth', 'preventBackHistory'])->group(function () {
        // Dashboard
        Route::controller(DashboardController::class)->group(function () {
            //Cá nhân
            Route::post('/logout', 'logoutHandler')->name('logout');
            Route::get('/profile', 'profileView')->name('profile');
            Route::post('/update-profile-picture/{id}', 'updateProfilePicture')->name('update_profile_picture');
            // Settings
            Route::get('/settings', 'generalSettings')->name('settings')->middleware('CheckPermission:admin.settings');
            // Settings Website
            Route::post('/update-logo', 'updateLogo')->name('update_logo');
            Route::post('/update-favicon', 'updateFavicon')->name('update_favicon');
            //Chung
            Route::get('/dashboard', 'dashboard')->name('dashboard');
            Route::get('/score', 'scoreView')->name('score');
        });
        // Attendance
        Route::prefix('attendance')->name('attendance.')->group(function () {
            Route::controller(AttendanceController::class)->group(function () {
                Route::get('/reward', 'rewardView')->name('reward')->middleware('CheckPermission:admin.attendance.reward');
                Route::get('/discipline', 'disciplineView')->name('discipline')->middleware('CheckPermission:admin.attendance.discipline');
                Route::get('/confirm', 'confirmView')->name('confirm')->middleware('CheckPermission:admin.attendance.confirm');
            });
        });
        //Personnel
        Route::prefix('personnel')->name('personnel.')->group(function () {
            Route::controller(PersonnelController::class)->group(function () {
                Route::get('/scouter', 'scouterView')->name('scouter')->middleware('CheckPermission:admin.personnel.scouter'); // huynh trưởng
                Route::get('/children', 'childrenView')->name('children')->middleware('CheckPermission:admin.personnel.children'); // thiếu nhi
            });
        });
        //Management
        Route::prefix('management')->name('management.')->group(function () {
            Route::controller(ManagementController::class)->group(function () {
                Route::get('/course', 'courseView')->name('course')->middleware('CheckPermission:admin.management.course'); // Lớp giáo lý
                Route::get('/sector', 'sectorView')->name('sector')->middleware('CheckPermission:admin.management.sector'); // nghành sinh hoạt
                Route::get('/bible', 'bibleView')->name('bible')->middleware('CheckPermission:admin.management.bible'); // Câu Kinh Thánh
                Route::get('/schedule', 'scheduleView')->name('schedule')->middleware('CheckPermission:admin.management.schedule'); // Lịch điểm danh
                Route::get('/role', 'roleView')->name('role')->middleware('CheckPermission:admin.management.role'); // Quản lý chức vụ
                Route::get('/permission', 'permissionView')->name('permission')->middleware('CheckPermission:admin.management.permission'); // Quản lý chức vụ
                Route::get('/regulation', 'regulationView')->name('regulation')->middleware('CheckPermission:admin.management.regulation'); // Quản lý chức vụ
                Route::get('/notice', 'noticeView')->name('notice')->middleware('CheckPermission:admin.management.notice'); // Quản lý chức vụ
                Route::get('/activity-logs', 'activityLogsView')->name('activity-logs')->middleware('CheckPermission:admin.management.activity-logs'); // Quản lý chức vụ
                Route::get('/transaction', 'transactionView')->name('transaction')->middleware('CheckPermission:admin.management.transaction'); // Quản lý chức vụ
            });
        });

        Route::prefix('support')->name('support.')->group(function () {
            Route::controller(SupportController::class)->group(function () {
                Route::get('/feedback', 'feedbackView')->name('feedback');
                Route::post('/feedback/upload-image', 'uploadImageFeedback')->name('feedback.upload_image');
                Route::post('/feedback/delete-image', 'deleteImageFeedback')->name('feedback.delete_image');

                Route::get('/complaint', 'complaintView')->name('complaint');
                Route::post('/complaint/upload-image', 'uploadImageComplaint')->name('complaint.upload_image');
                Route::post('/complaint/delete-image', 'deleteImageComplaint')->name('complaint.delete_image');

                Route::get('/assigned', 'assignedView')->name('assigned')->middleware('CheckPermission:admin.support.assigned');
                Route::get('/resolve', 'resolveView')->name('resolve')->middleware('CheckPermission:admin.support.resolve');
            });
        });

    });
});
