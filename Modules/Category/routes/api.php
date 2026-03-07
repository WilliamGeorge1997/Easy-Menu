<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\CategoryController;
use Modules\Category\Http\Controllers\Api\CategoryController as CategoryApiController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('categories', \Modules\Category\Http\Controllers\CategoryController::class)->names('category');
});

Route::prefix('v1')->group(function () {

    Route::get('categories/{id}/products', [CategoryApiController::class, 'products']);

});
