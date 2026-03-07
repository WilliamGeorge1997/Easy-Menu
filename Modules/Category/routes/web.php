<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\CategoryController;

Route::group([
    'middleware' => ['web', 'auth:admin', 'admin.locale'],
    'prefix'     => 'admin',
    'as'         => 'admin.',
], function () {

    // Standard CRUD (index, create, store, edit, update, destroy)
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Toggle active status
    Route::post('categories/{category}/activate', [CategoryController::class, 'activate'])
        ->name('categories.activate');
});
