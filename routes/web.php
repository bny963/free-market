<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

Route::get('/', [ItemController::class, 'index'])->name('index');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');
Route::post('/item/{item_id}/like', [ItemController::class, 'toggleLike'])->name('item.like');
Route::post('/item/{item_id}/comment', [ItemController::class, 'storeComment'])->name('item.comment');

// 仮のプロフィール設定画面（FN006の遷移先確認用）
Route::get('/profile', function () {
    return view('profile-placeholder'); 
})->middleware(['auth', 'verified'])->name('profile');
