<?php

use App\Http\Controllers\ChirpController;
use App\Http\Controllers\Product\CheckController;
use App\Http\Controllers\Product\CreateController;
use App\Http\Controllers\Product\EditController;
use App\Http\Controllers\Product\InfoController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

//訪客首頁
Route::get('/', function () {
    return view('Home');
});

Route::get('/s', function () {
    return view('test');
});

Route::get('/tag-index', [TagController::class, 'index'])->name('tags.index');
Route::get('/tag-create', [TagController::class, 'create'])->name('tags.create');
Route::get('/tag-edit/{id}', [TagController::class, 'edit'])->name('tags.edit');
Route::post('/tag-store', [TagController::class, 'store'])->name('tags.store');
Route::put('/tag-update/{id}', [TagController::class, 'update'])->name('tags.update');
Route::delete('/tag-destroy/{id}', [TagController::class, 'destroy'])->name('tags.destroy');
Route::post('/tag-restore/{id}', [TagController::class, 'restore'])->name('tags.restore');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::get('/user-product-info/{product}', [InfoController::class, 'index'])->name('products.info');

//商品檢舉
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');

//登入: product_create
Route::get('/products-create', [CreateController::class, 'create'])->name('products.create');

//送出表單: product_create
Route::post('/user-product-create', [CreateController::class, 'store'])->name('products.store');

Route::get('/products-check', [CheckController::class, 'index'])->name('products.check');

Route::put('/user-product-check/{product}', [CheckController::class, 'demoteData'])->name('products.demoteData');

Route::delete('/user-product-check/{product}', [CheckController::class, 'destroy'])->name('products.destroy');

Route::put('/user-product-edit/{product}', [CheckController::class, 'update'])->name('products.update');

Route::get('/user-product-edit/{product}', [EditController::class, 'edit'])->name('products.edit');

Route::get('/admin/search', [ChirpController::class, 'adminSearch'])->name('admin.search');

Route::delete('/users/{user}', [UserController::class, 'destroy'])
    ->name('users.destroy')
    ->middleware('admin');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/control', [ProfileController::class, 'control'])->name('profile.partials.control');
});

Route::resource('products.chirps', ChirpController::class)
    ->only(['index', 'store', 'edit', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::delete('/chirps/{chirp}', [ChirpController::class, 'destroy'])
    ->name('chirps.destroy')
    ->middleware(['auth', 'verified']);

// Route::middleware(['auth', 'admin'])->group(function () {
Route::get('/admin/user', [UserController::class, 'index'])->name('admin.user.index');
Route::post('/user/suspend', [UserController::class, 'suspend'])->name('user.suspend');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('ausers.destroy');
// });

Route::get('/dashboard', function () {
    return view('Home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/products/{productId}', [checkController::class, 'index'])->name('products.info');
Route::post('/products/{product}/demote', [checkController::class, 'demoteData'])->name('products.demote');

Route::post('/admin/{id}/create', [PermissionController::class, 'create'])->name('admin.create');
Route::post('/admin/{id}/update', [PermissionController::class, 'update'])->name('admin.update');
require __DIR__.'/auth.php';
