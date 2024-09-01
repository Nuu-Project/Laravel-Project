<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

//訪客首頁
Route::get('/', function () {
    return view('Home');
});

//已登入首頁
Route::get('/user_home', function () {
    return view('Home_user');
});

//共用: product
Route::get('/product', function () { 
    return view('Product');
});

//共用: product
Route::get('/user_product', function () { 
    return view('Product_user');
});

//登入: product_create
Route::get('/user_product_create', function () {
    return view('Product_create');
});

//登入: 查看用戶刊登商品
Route::get('/user_product_check', function () {
    return view('Product_check');
});

Route::get('/dashboard', function () {
    return view('Home_user');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
