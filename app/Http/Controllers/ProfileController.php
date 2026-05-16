<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * FN025: ユーザー情報・マイページ取得
     */
    public function show(Request $request)
    {
        $user = Auth::user();

        // 表示するタブの切り替え（デフォルトは 'sell' = 出品した商品）
        $tab = $request->query('tab', 'sell');

        if ($tab === 'buy') {
            // FN025-4: 購入した商品一覧（Orderから商品情報を引っ張る）
            $orders = Order::with('item')->where('user_id', $user->id)->latest()->get();
            // コレクションからアイテムだけを抽出
            $items = $orders->pluck('item')->filter();
        } else {
            // FN025-3: 自分が出品した商品一覧
            $items = Item::where('user_id', $user->id)->latest()->get();
        }

        return view('profile.show', compact('user', 'items', 'tab'));
    }
}