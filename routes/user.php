<?php

use App\Http\Controllers\User\MessageController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->name('user.')->middleware(['auth', 'verified'])->group(function () {
    // 我的商品頁面
    Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index');
    // 商品上下架
    Route::put('/products/{product}/demote', [ProductController::class, 'demoteData'])
        ->name('products.demoteData');
    // 商品修改頁面
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
        ->name('products.edit');
    // 商品>修改 資料
    Route::put('/products/{product}', [ProductController::class, 'update'])
        ->name('products.update');

    // 商品刪除
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->name('products.destroy');

    // 刊登商品頁面
    Route::get('/products/create', [ProductController::class, 'create'])
        ->name('products.create');
    // 商品建立資料
    Route::post('/products', [ProductController::class, 'store'])
        ->name('products.store');

    // 留言 建立,編輯頁,更新留言,刪除
    Route::resource('products.chirps', MessageController::class)
        ->only(['store', 'edit', 'update', 'destroy']);

    // 商品檢舉
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');

});

//
// 個人資料 頁面,修改密碼,刪除帳號
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
