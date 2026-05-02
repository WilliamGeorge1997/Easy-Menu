<?php

use Illuminate\Support\Facades\Route;
use Modules\Package\Http\Controllers\PackageController;

Route::prefix('admin')->name('admin.')->middleware(['web', 'auth:admin', 'admin.locale'])->group(function () {
    Route::post('packages/{package}/activate', [PackageController::class, 'activate'])->name('packages.activate');
    Route::resource('packages', PackageController::class)->except(['show']);
});
