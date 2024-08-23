<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Home');
});

Route::get('/store', function () {
    return view('Store');
});

Route::get('/add', function () {
    return view('Add');
});

Route::get('/login', function () {
    return view('Login');
});

Route::get('/regist', function () {
    return view('Regist');
});

