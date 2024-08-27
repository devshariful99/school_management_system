<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Admin\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () {

    Route::get('/dashboard', [DashboardController::class,'dashboard'])->name('admin.dashboard');


    // // Admin Management Routes
    // Route::group(['as' => 'am.', 'prefix' => 'admin-management'], function () {
    //     Route::controller(AdminController::class)->prefix('admin')->name('admin.')->group(function () {
    //         Route::get('index', 'index')->name('admin_list');
    //         Route::get('details/{id}', 'details')->name('details.admin_list');
    //         Route::get('profile/{id}', 'profile')->name('admin_profile');
    //         Route::get('create', 'create')->name('admin_create');
    //         Route::post('create', 'store')->name('admin_create');
    //         Route::get('edit/{id}', 'edit')->name('admin_edit');
    //         Route::put('edit/{id}', 'update')->name('admin_edit');
    //         Route::get('status/{id}', 'status')->name('status.admin_edit');
    //         Route::get('delete/{id}', 'delete')->name('admin_delete');
    //     });
    //     Route::controller(PermissionController::class)->prefix('permission')->name('permission.')->group(function () {
    //         Route::get('index', 'index')->name('permission_list');
    //         Route::get('details/{id}', 'details')->name('details.permission_list');
    //         Route::get('create', 'create')->name('permission_create');
    //         Route::post('create', 'store')->name('permission_create');
    //         Route::get('edit/{id}', 'edit')->name('permission_edit');
    //         Route::put('edit/{id}', 'update')->name('permission_edit');
    //     });
    //     Route::controller(AdminRoleController::class)->prefix('role')->name('role.')->group(function () {
    //         Route::get('index', 'index')->name('role_list');
    //         Route::get('details/{id}', 'details')->name('details.role_list');
    //         Route::get('create', 'create')->name('role_create');
    //         Route::post('create', 'store')->name('role_create');
    //         Route::get('edit/{id}', 'edit')->name('role_edit');
    //         Route::put('edit/{id}', 'update')->name('role_edit');
    //         Route::get('delete/{id}', 'delete')->name('role_delete');
    //     });
    // });
});