<?php

use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('tags', TagController::class)->except(['show']);
        Route::post('/tags/{id}/restore', [TagController::class, 'restore'])
            ->name('tags.restore');
    });
});