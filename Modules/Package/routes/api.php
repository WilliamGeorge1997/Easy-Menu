<?php

use Illuminate\Support\Facades\Route;
use Modules\Package\Http\Controllers\Api\PackageController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('packages', PackageController::class)->names('package');
});
