<?php

use App\Http\Controllers\Admin\Report\ReportDetailController;
use App\Http\Controllers\ChirpController;
use App\Http\Controllers\DownShelvesController;
use App\Http\Controllers\ManageableProductsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('/admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    // 商品管理頁
    Route::get('/products', [ManageableProductsController::class, 'index'])
        ->name('products.index');
    // 商品管理下架 未返回畫面
    Route::put('/products/{product}', [DownShelvesController::class, 'demoteData'])
        ->name('products.demote');

    // 用戶管理頁
    Route::get('/user', [UserController::class, 'index'])
        ->name('users.index');
    // 用戶停用
    Route::post('/user/suspend', [UserController::class, 'suspend'])
        ->name('users.suspend');

    // 留言管理頁 controller要改
    Route::get('/message', [ChirpController::class, 'adminMessage'])
        ->name('messages.index');

    // 角色管理路由
    Route::get('/role', [RoleController::class, 'index'])
        ->name('roles.index');
    // ??
        Route::post('/role', [RoleController::class, 'store'])
        ->name('roles.store');

    // 標籤 頁面,新增,修改,刪除
    Route::resource('/tags', TagController::class)
        ->except(['show'])
        ->withTrashed();
    // 標籤 啟用
    Route::post('/tags/{tag}/restore', [TagController::class, 'restore'])
        ->name('tags.restore')
        ->withTrashed();

    // 檢舉詳情頁
    Route::get('/reports', [ReportDetailController::class, 'index'])
        ->name('reports.index');
});
