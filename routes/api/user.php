<?php

use App\Http\Controllers\Api\User\ProductProcessImageController;
use App\Http\Controllers\Api\User\ProductReportableController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->name('user.')->middleware(['auth', 'verified'])->group(function () {

    Route::post('products/{product}/reportables', [ProductReportableController::class, 'store'])
        ->name('products.reportables.store');

    Route::post('/products/processimage', [ProductProcessImageController::class, 'processImage'])
        ->name('products.processimage');
});
