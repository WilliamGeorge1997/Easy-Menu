<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\Api\ProductController;

Route::prefix('v1')->group(function () {

    Route::get('products/categories-by-branch/{branchId}', [ProductController::class, 'index']);

});
