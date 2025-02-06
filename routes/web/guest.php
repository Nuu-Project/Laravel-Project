<?php

use App\Http\Controllers\Web\Guest\HomeController;
use App\Http\Controllers\Web\Guest\ProductController;
use Illuminate\Support\Facades\Route;

// 首頁
Route::get('/', [HomeController::class, 'index'])
    ->name('dashboard');

// 商品 瀏覽頁,洽談頁
Route::resource('products', ProductController::class)
    ->only(['index', 'show']);
