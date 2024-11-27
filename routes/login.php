<?php

use App\Http\Controllers\login\Product\MessageController;
use App\Http\Controllers\login\Product\ReportController;
use App\Http\Controllers\login\ProfileController;
use Illuminate\Support\Facades\Route;

// 留言 建立,編輯頁,更新留言,刪除
Route::resource('products.chirps', MessageController::class)
    ->only(['store', 'edit', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

// 商品檢舉
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');

//
// 個人資料 頁面,修改密碼,刪除帳號
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
