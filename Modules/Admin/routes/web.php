<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminAuthController;
use Modules\Admin\Http\Controllers\AdminController;
use Modules\Admin\Http\Controllers\RoleController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminAuthController::class, 'loginForm'])->name('login.form');
        Route::post('login', [AdminAuthController::class, 'login'])->name('login');
    });

    Route::middleware(['auth:admin', 'admin.locale'])->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        // Language
        Route::post('language/{lang}', [AdminController::class, 'setLanguage'])->name('language.switch');
        // Profile
        Route::get('profile/edit', [AdminController::class, 'editProfile'])->name('profile.edit');
        Route::put('profile', [AdminController::class, 'updateProfile'])->name('profile.update');

        // Admins
        Route::post('admins/{id}/activate', [AdminController::class, 'activate'])->name('admins.activate');
        Route::resource('admins', AdminController::class);
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    });
});
