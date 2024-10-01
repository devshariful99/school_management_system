<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Backend\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Backend\Admin\DashboardController;
use App\Http\Controllers\Backend\Admin\AdminManagement\AdminController;
use App\Http\Controllers\Backend\Admin\AdminManagement\PermissionController;
use App\Http\Controllers\Backend\Admin\AdminManagement\RoleController;
use App\Http\Controllers\Backend\Admin\DatatableController as AdminDatatableController;
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

    // Admin Management
    Route::group(['as' => 'am.', 'prefix' => 'admin-management'], function () {
        Route::resource('admin', AdminController::class);
        Route::get('admin/status/{admin}', [AdminController::class, 'status'])->name('admin.status');
        Route::resource('role', RoleController::class);
        Route::get('role/status/{role}', [RoleController::class, 'status'])->name('role.status');
        Route::resource('permission', PermissionController::class);
        Route::get('permission/status/{permission}', [PermissionController::class, 'status'])->name('permission.status');
    });
});