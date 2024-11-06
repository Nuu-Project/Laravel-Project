<?php

use App\Http\Controllers\Admin\Report\ReportDetailController;
use App\Http\Controllers\ChirpController;
use App\Http\Controllers\DownShelvesController;
use App\Http\Controllers\ManageableProductsController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//
// 商品管理頁
Route::get('/admin/product', [ManageableProductsController::class, 'index'])->name('ManageProducts.index');

// 商品管理下架
Route::put('/products/{product}/demote', [DownShelvesController::class, 'demoteData'])->name('DownShelvesController.demote');

//
// 用戶管理頁
Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/admin/user', [UserController::class, 'index'])->name('admin.user.index');
});

// 用戶停用
Route::post('/user/suspend', [UserController::class, 'suspend'])->name('user.suspend');

//
// 留言管理頁 controller要改
Route::get('/admin/message', [ChirpController::class, 'adminMessage'])->name('admin.message');

//
// 標籤管理
Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::post('/tags/{tag}/restore', [TagController::class, 'restore'])
            ->name('tags.restore')
            ->withTrashed();
        Route::resource('tags', TagController::class)
            ->except(['show'])
            ->withTrashed();
    });
});

//
// 檢舉詳情頁
Route::get('/admin/report', [ReportDetailController::class, 'index'])->name('report.index');
