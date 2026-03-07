<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\ProductController;

Route::prefix('admin')->name('admin.')->middleware(['web', 'auth:admin', 'admin.locale'])->group(function () {

    // These explicit routes MUST come before Route::resource() to avoid
    // Laravel matching them as {product} route model binding
    Route::delete('products/images/{imageId}', [ProductController::class, 'destroyImage'])->name('products.images.destroy');
    Route::post('products/{product}/activate', [ProductController::class, 'activate'])->name('products.activate');

    Route::resource('products', ProductController::class)->except(['show']);
});
