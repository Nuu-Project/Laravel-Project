<?php

use App\Http\Controllers\User\MessageController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->name('user.')->middleware(['auth', 'verified'])->group(function () {
    // 我的商品頁,刊登頁,建立商品,編輯頁,更新商品,刪除
    Route::resource('products', ProductController::class)
        ->except(['show']);
    // 商品上下架
    Route::put('/products/{product}/inactive', [ProductController::class, 'dinactive'])
        ->name('products.inactive');

    // 留言 建立,編輯頁,更新留言,刪除
    Route::resource('products.messages', MessageController::class)
        ->only(['store', 'edit', 'update', 'destroy']);

    // 商品檢舉
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');

    // 個人資料 頁面,修改密碼,刪除帳號
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
