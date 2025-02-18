<?php

use App\Http\Controllers\Api\User\ProductProcessImageController;
use App\Http\Controllers\Api\User\ProductReportController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::post('products/{product}/reportables', [ProductReportController::class, 'store'])
        ->name('products.reportables.store');

    Route::post('/products/process-image', [ProductProcessImageController::class, 'processImage'])
        ->middleware(['web'])
        ->name('products.process-image');
});
