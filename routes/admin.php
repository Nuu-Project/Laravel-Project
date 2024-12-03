<?php

use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportDetailController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ShelvesController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    // 商品管理頁 上架按鈕的字顯示錯誤
    Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index');
    // 商品管理下架 未返回畫面
    Route::put('/products/{product}/inactive', [ProductController::class, 'demoteData'])
        ->name('products.demote');

    // 用戶管理頁
    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');
    // 用戶停用
    Route::post('/users/{user}/suspend', [UserController::class, 'suspend'])
        ->name('users.suspend');

    // 留言管理頁 controller要改
    Route::get('/messages', [MessageController::class, 'index'])
        ->name('messages.index');

    // 角色管理路由
    Route::resource('roles', RoleController::class)
        ->only(['index', 'store', 'edit', 'update', 'create']);

    // 標籤 頁面,新增,修改,刪除
    Route::resource('tags', TagController::class)
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
