<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// 仮のプロフィール設定画面（FN006の遷移先確認用）
Route::get('/profile', function () {
    return view('profile-placeholder'); 
})->middleware(['auth', 'verified'])->name('profile');
