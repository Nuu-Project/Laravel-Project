<?php

use App\Http\Controllers\Api\Admin\UserSuspendController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    // 用戶停用
    Route::post('/users/{user}/suspend', [UserSuspendController::class, 'suspend'])
        ->name('users.suspend');
}
);
