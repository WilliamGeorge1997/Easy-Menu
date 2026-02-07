<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminAuthController;
use Modules\Admin\Http\Middleware\SetAdminLocale;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminAuthController::class, 'loginForm'])->name('login.form');
        Route::post('login', [AdminAuthController::class, 'login'])->name('login');
    });

    Route::middleware(['auth:admin', SetAdminLocale::class])->group(function () {
        Route::get('dashboard', [AdminAuthController::class, 'loginForm'])->name('dashboard');
    });
});
