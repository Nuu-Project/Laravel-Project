<?php

use App\Http\Controllers\Admin\Report\ReportDetailController;
use App\Http\Controllers\ChirpController;
use App\Http\Controllers\DownShelvesController;
use App\Http\Controllers\ManageableProductsController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Product\CheckController;
use App\Http\Controllers\Product\CreateController;
use App\Http\Controllers\Product\EditController;
use App\Http\Controllers\Product\InfoController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Monolog\Handler\RotatingFileHandler;

//訪客首頁
Route::get('/', function () {
    return view('Home');
});

Route::get('/s', function () {
    return view('test');
});

//送出表單: product_create
Route::post('/user-product-create', [CreateController::class, 'store'])->name('products.store');

Route::get('/products-check', [CheckController::class, 'index'])->name('products.check');

Route::put('/user-product-check/{product}', [CheckController::class, 'demoteData'])->name('products.demoteData');

Route::delete('/user-product-check/{product}', [CheckController::class, 'destroy'])->name('products.destroy');

Route::put('/user-product-edit/{product}', [CheckController::class, 'update'])->name('products.update');

Route::get('/user-product-edit/{product}', [EditController::class, 'edit'])->name('products.edit');

Route::get('/admin/message', [ChirpController::class, 'adminMessage'])->name('admin.message');

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

Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/admin/user', [UserController::class, 'index'])->name('admin.user.index');
});
Route::post('/user/suspend', [UserController::class, 'suspend'])->name('user.suspend');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('ausers.destroy');

Route::get('/dashboard', function () {
    return view('Home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin/product', [ManageableProductsController::class, 'index'])->name('ManageProducts.index');
Route::put('/products/{product}/demote', [DownShelvesController::class, 'demoteData'])->name('DownShelvesController.demote');
Route::delete('/products/{product}/images/{image}', [CheckController::class, 'deleteImage'])->name('products.deleteImage');

Route::get('/admin/report', [ReportDetailController::class, 'index'])->name('report.index');

Route::post('/admin/{id}/create', [PermissionController::class, 'create'])->name('admin.create');
Route::post('/admin/{id}/update', [PermissionController::class, 'update'])->name('admin.update');
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
