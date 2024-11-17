<?php

use App\Http\Controllers\ChirpController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ReportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

//
// 訪客首頁?
Route::get('/', function () {
    return view('Home');
});

// 登入後的首頁?
Route::get('/dashboard', function () {
    return view('Home');
})->middleware(['auth', 'verified'])->name('dashboard');

//
// 商品 瀏覽頁,洽談頁
Route::resource('products', ProductController::class)
    ->only(['index', 'show']);

// 留言 建立,編輯頁,更新留言,刪除
Route::resource('products.chirps', ChirpController::class)
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

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/user.php';
