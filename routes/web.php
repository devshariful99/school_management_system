<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Backend\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Backend\Admin\DashboardController;
use App\Http\Controllers\Backend\Admin\AdminManagement\AdminController;
use App\Http\Controllers\Backend\Admin\AdminManagement\PermissionController;
use App\Http\Controllers\Backend\Admin\AdminManagement\RoleController;
use App\Http\Controllers\Backend\Admin\AuditController;
use App\Http\Controllers\Backend\Admin\DatatableController as AdminDatatableController;
use App\Http\Controllers\Backend\Admin\FileManagementController as AdminFileManagementController;
use App\Http\Controllers\Backend\SiteSettingController;
use Illuminate\Support\Facades\Response;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Admin Login Routes
Route::controller(AdminLoginController::class)->prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', 'adminLogin')->name('login');
    Route::post('/login', 'adminLoginCheck')->name('login');
    Route::post('/logout', 'logout')->name('logout');
});



Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function () {

    //Developer Routes
    Route::get('/export-permissions', function () {
        $filename = 'permissions.csv';
        $filePath = createCSV($filename);
        return Response::download($filePath, $filename);
    })->name('permissions.export');

    Route::post('update/sort/order', [AdminDatatableController::class, 'updateSortOrder'])->name('update.sort.order');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');




    // File Management
    Route::controller(AdminFileManagementController::class)->prefix('file-management')->name('file.')->group(function () {
        Route::post('/upload-temp-file', 'uploadTempFile')->name('upload_tf');
        Route::delete('/delete-temp-file', 'deleteTempFile')->name('delete_tf');
        Route::post('/reset-file-file', 'resetTempFile')->name('reset_tf');
        // Route::post('/cleanup-temp-files', 'cleanupTempFiles')->name('cleanup_tf');
        Route::post('/delete-unsaved-temp-files', 'deleteUnsavedTempFiles')->name('du_tf');
    });
    // Admin Management
    Route::group(['as' => 'am.', 'prefix' => 'admin-management'], function () {
        Route::resource('admin', AdminController::class);
        Route::get('admin/status/{admin}', [AdminController::class, 'status'])->name('admin.status');
        Route::resource('role', RoleController::class);
        Route::get('role/status/{role}', [RoleController::class, 'status'])->name('role.status');
        Route::resource('permission', PermissionController::class);
        Route::get('permission/status/{permission}', [PermissionController::class, 'status'])->name('permission.status');
    });

    // Audit Management
    Route::controller(AuditController::class)->prefix('audits')->name('audit.')->group(function () {
        Route::get('audits', 'index')->name('index');
        Route::get('audits/details/{id}', 'details')->name('details');
    });

    // Site Settings
    Route::controller(SiteSettingController::class)->prefix('site-settings')->name('site_setting.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('update', 'update')->name('update');
        Route::get('email-template/edit/{id}', 'et_edit')->name('email_template');
        Route::put('email-template/edit/{id}', 'et_update')->name('email_template');
        Route::post('notification/update', 'notification')->name('notification');
    });
});
