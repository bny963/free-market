<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// 仮のプロフィール設定画面（FN006の遷移先確認用）
Route::get('/profile', function () {
    return '<h1>プロフィール設定画面（ここへ遷移すればFN006成功です）</h1>';
})->middleware(['auth', 'verified'])->name('profile');
