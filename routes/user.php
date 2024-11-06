<?php

use App\Http\Controllers\Product\CheckController;
use App\Http\Controllers\Product\CreateController;
use App\Http\Controllers\Product\EditController;
use Illuminate\Support\Facades\Route;

// 
// 商品建立頁面
Route::get('/products-create', [CreateController::class, 'create'])->name('products.create');

// 商品建立資料
Route::post('/user-product-create', [CreateController::class, 'store'])->name('products.store');

// 
// 我的商品頁面
Route::get('/products-check', [CheckController::class, 'index'])->name('products.check');

// 商品上下架
Route::put('/user-product-check/{product}', [CheckController::class, 'demoteData'])->name('products.demoteData');

// 商品修改頁面
Route::get('/user-product-edit/{product}', [EditController::class, 'edit'])->name('products.edit');

// 商品修改資料
Route::put('/user-product-edit/{product}', [CheckController::class, 'update'])->name('products.update');

// 商品修改圖片刪除
Route::delete('/products/{product}/images/{image}', [CheckController::class, 'deleteImage'])->name('products.deleteImage');

// 商品刪除 霖,新增按鈕並加到此Route
Route::delete('/user-product-check/{product}', [CheckController::class, 'destroy'])->name('products.destroy');
