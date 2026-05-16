<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

Route::get('/', [ItemController::class, 'index'])->name('index');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');
Route::post('/item/{item_id}/like', [ItemController::class, 'toggleLike'])->name('item.like');
Route::post('/item/{item_id}/comment', [ItemController::class, 'storeComment'])->name('item.comment');

Route::get('/profile', function () {
    // ログインユーザーが購入した履歴（商品情報を含む）を取得
    $orders = Order::with('item')->where('user_id', Auth::id())->latest()->get();

    return view('profile-placeholder', compact('orders'));
})->middleware(['auth', 'verified'])->name('profile');

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
});
