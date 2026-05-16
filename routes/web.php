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

    // FN025: マイページ表示
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

    // FN026: プロフィール編集画面（動線確認用の仮ルート）
    Route::get('/profile/edit', function () {
        return "ここはプロフィール編集画面です（US008で実装予定）";
    })->name('profile.edit');

    // FN025: マイページ表示
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

    // FN027: プロフィール編集画面表示（ここを修正）
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    // FN027: プロフィール変更保存処理（ここを追記）
    Route::post('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');

    // FN028: 商品出品
    Route::get('/sell', [ItemController::class, 'create'])->name('item.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('item.store');
});
