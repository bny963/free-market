<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;

Route::get('/', [ItemController::class, 'index'])->name('index');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');
Route::post('/item/{item_id}/like', [ItemController::class, 'toggleLike'])->name('item.like');
Route::post('/item/{item_id}/comment', [ItemController::class, 'storeComment'])->name('item.comment');


Route::middleware(['auth', 'verified'])->group(function () {
    // 購入画面表示
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'showPurchase'])->name('purchase.show');

    // 配送先変更画面表示＆処理
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'showAddress'])->name('purchase.address');
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])->name('purchase.address.update');

    // Stripe決済開始（POST）
    Route::post('/purchase/checkout/{item_id}', [PurchaseController::class, 'checkout'])->name('purchase.checkout');

    // Stripeから戻ってくる決済完了画面（GET）
    Route::get('/purchase/success/{item_id}', [PurchaseController::class, 'success'])->name('purchase.success');

    // FN025: マイページ表示
    Route::get('/mypage', [ProfileController::class, 'show'])->name('profile');

    // FN027: プロフィール編集画面表示（ここを修正）
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // FN027: プロフィール変更保存処理（ここを追記）
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');

    // FN028: 商品出品
    Route::get('/sell', [ItemController::class, 'create'])->name('item.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('item.store');

    // 商品出品削除（安全のためPOSTで処理）
    Route::post('/item/{item_id}/delete', [ItemController::class, 'destroy'])->name('item.destroy');
});
