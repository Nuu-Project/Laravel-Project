<?php

use App\Http\Controllers\Guest\ProductController;
use Illuminate\Support\Facades\Route;

// 首頁
Route::get('/', function () {
    return view('Home');
})->name('dashboard');

// 商品 瀏覽頁,洽談頁
Route::resource('products', ProductController::class)
    ->only(['index', 'show']);

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/user.php';
