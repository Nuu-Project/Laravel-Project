<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('user')->name('user.')->group(function () {
    Route::post('/products/process-image', [App\Http\Controllers\Api\User\ProductProcessImageController::class, 'processImage'])
        ->name('products.process-image');
});

require __DIR__.'/api/product.php';
require __DIR__.'/api/admin.php';
