<?php

use Illuminate\Support\Facades\Route;
use Modules\Branch\Http\Controllers\Api\BranchController;

Route::prefix('v1')->group(function () {

    Route::get('branches/{slug}',            [BranchController::class, 'show']);
    Route::get('branches/{slug}/categories', [BranchController::class, 'categories']);

});
