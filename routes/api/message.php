<?php

use App\Http\Controllers\Api\User\MessageReportController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('/messages/{message}/reports', [MessageReportController::class, 'store'])
        ->name('messages.reports.store');
});
