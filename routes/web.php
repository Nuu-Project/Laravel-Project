<?php

use Illuminate\Support\Facades\Route;



//訪客: 首頁
Route::get('/', function () {
    return view('Home');
});

//已登入: 首頁
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

//訪客首頁: 登入
Route::get('/login', function () {
    return view('Login');
});

//共用: 註冊
Route::get('/register', function () {
    return view('Register');
});

//共用: 註冊
Route::get('/user_register', function () {
    return view('Register');
});

//登入: 查看用戶刊登商品
Route::get('/user_product_check', function () {
    return view('Product_check');
});

