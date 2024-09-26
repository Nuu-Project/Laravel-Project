<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

//訪客首頁
Route::get('/', function () {
    return view('Home');
});


Route::get('/s', function () {
    return view('test');
});

//共用: product
// Route::get('/product', function () { 
//     return view('Product');
// });

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

//登入: product_create
Route::get('/products-create', [ProductController::class, 'create'])->name('products.create');

//送出表單: product_create
Route::post('/user-product-create', [ProductController::class, 'store'])->name('products.store');

//登入: 查看用戶刊登商品
// Route::get('/user-product-check', function () {
//     return view('Product-check');
// });
Route::get('/products-check', [ProductController::class, 'index'])->name('products.check');

Route::put('/user-product-check/{product}', [ProductController::class, 'update'])->name('products.update');
    
Route::delete('/user-product-check/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

Route::get('/user-product-info', [ProductController::class, 'index'])->name('products.info');

// Route::get('/product-info', function () {
//     return view('Product-info');
// });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    return view('Home');
})->middleware(['auth', 'verified'])->name('dashboard');
require __DIR__.'/auth.php';
