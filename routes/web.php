<?php

use App\Http\Controllers\Guest\ProductController;
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

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/user.php';
require __DIR__.'/login.php';
