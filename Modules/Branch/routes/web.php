<?php

use Illuminate\Support\Facades\Route;
use Modules\Branch\Http\Controllers\BranchController;

Route::prefix('admin')->name('admin.')->middleware(['web', 'auth:admin', 'admin.locale'])->group(function () {
    Route::resource('branches', BranchController::class)->except(['show']);
    Route::post('branches/{branch}/activate', [BranchController::class, 'activate'])->name('branches.activate');
    Route::get('branches/{branch}/work-hours', [BranchController::class, 'editWorkHours'])->name('branches.work-hours.edit');
    Route::put('branches/{branch}/work-hours', [BranchController::class, 'updateWorkHours'])->name('branches.work-hours.update');
    Route::get('branches/{branch}/settings', [BranchController::class, 'editSettings'])->name('branches.settings.edit');
    Route::put('branches/{branch}/settings', [BranchController::class, 'updateSettings'])->name('branches.settings.update');
});
