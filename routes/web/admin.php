<?php

use App\Http\Controllers\Web\Admin\MessageController;
use App\Http\Controllers\Web\Admin\ProductController;
use App\Http\Controllers\Web\Admin\ReportController;
use App\Http\Controllers\Web\Admin\ReportTypeController;
use App\Http\Controllers\Web\Admin\RoleController;
use App\Http\Controllers\Web\Admin\TagController;
use App\Http\Controllers\Web\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    // 商品管理頁 上架按鈕的字顯示錯誤
    Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index');
    // 商品管理下架 未返回畫面
    Route::put('/products/{product}/inactive', [ProductController::class, 'inactive'])
        ->name('products.inactive');

    // 用戶管理頁
    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');

    // 留言管理頁 controller要改
    Route::get('/messages', [MessageController::class, 'index'])
        ->name('messages.index');

    // 角色管理路由
    Route::resource('roles', RoleController::class)
        ->only(['index', 'store', 'update', 'create']);

    // 標籤 頁面,新增,修改,刪除
    Route::resource('tags', TagController::class)
        ->except(['show'])
        ->withTrashed();
    // 標籤 啟用
    Route::post('/tags/{tag}/restore', [TagController::class, 'restore'])
        ->name('tags.restore')
        ->withTrashed();

    // 檢舉詳情頁
    Route::resource('/reports', ReportController::class)
        ->only(['index', 'show']);

    // 檢舉類型 頁面,新增,修改,刪除
    Route::resource('report_types', ReportTypeController::class)
        ->except(['show'])
        ->withTrashed();
    // 檢舉類型 啟用
    Route::post('/report_types/{report_type}/restore', [ReportTypeController::class, 'restore'])
        ->name('report_types.restore')
        ->withTrashed();
});
