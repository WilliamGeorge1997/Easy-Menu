<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminController;
use Modules\Admin\Http\Controllers\AdminAuthController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminAuthController::class, 'loginForm'])->name('login.form');
        Route::post('login', [AdminAuthController::class, 'login'])->name('login');
    });

    Route::middleware(['auth:admin', 'admin.locale'])->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        //Language
        Route::post('language/{lang}', [AdminController::class, 'setLanguage'])->name('language.switch');

        //Admins
        Route::post('admins/{id}/activate', [AdminController::class, 'activate'])->name('admins.activate');
        Route::resource('admins', AdminController::class);
    });
});
