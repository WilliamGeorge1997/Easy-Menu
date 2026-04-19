<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\CategoryController;
use Modules\Category\Http\Controllers\Api\CategoryController as CategoryApiController;

Route::prefix('v1')->group(function () {

    Route::get('categories/{id}/products', [CategoryApiController::class, 'products']);

});
