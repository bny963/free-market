<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

Route::get('/', [ItemController::class, 'index'])->name('index');

// 仮のプロフィール設定画面（FN006の遷移先確認用）
Route::get('/profile', function () {
    return view('profile-placeholder'); 
})->middleware(['auth', 'verified'])->name('profile');
