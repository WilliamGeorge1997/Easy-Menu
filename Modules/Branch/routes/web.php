<?php

use Illuminate\Support\Facades\Route;
use Modules\Branch\Http\Controllers\BranchController;

Route::prefix('admin')->name('admin.')->middleware(['web', 'auth:admin', 'admin.locale'])->group(function () {
    Route::resource('branches', BranchController::class)->except(['show']);
    Route::post('branches/{branch}/activate', [BranchController::class, 'activate'])->name('branches.activate');
});
