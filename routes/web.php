<?php

use App\Http\Controllers\ChirpController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ReportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// 測試用
Route::get('/s', function () {
    return view('test');
});

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
// 商品瀏覽頁
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// 商品洽談頁(留言紐and管理者刪除留言) 姚,view要改成連下面的Route 並刪除此Route
Route::get('/user-product-info/{product}', [ChirpController::class, 'index'])->name('products.info');

// 留言 頁面,建立,編輯頁,更新留言,刪除
Route::resource('products.chirps', ChirpController::class)
    ->only(['index', 'store', 'edit', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

// 商品檢舉
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');

// 
// 個人資料 頁面,修改密碼,刪除帳號,無用舊路徑前端頁面更新完需刪除
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/control', [ProfileController::class, 'control'])->name('profile.partials.control');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/user.php';
