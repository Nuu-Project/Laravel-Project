<?php

use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::post('/tags/{tag}/restore', [TagController::class, 'restore'])
            ->name('tags.restore')
            ->withTrashed();
        Route::resource('tags', TagController::class)
            ->except(['show'])
            ->withTrashed();
    });
});