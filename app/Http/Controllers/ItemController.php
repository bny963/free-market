<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        // 検索キーワードの取得
        $keyword = $request->input('keyword');

        // 現在のタブ（デフォルトは 'index' = 全商品）
        $page = $request->query('page', 'index');

        // クエリのベース作成
        $query = Item::query();

        // FN016: 商品名での部分一致検索
        if ($keyword) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        if ($page === 'mylist') {
            // FN015: マイリスト（いいねした商品）
            if (Auth::check()) {
                $query->whereHas('likes', function ($q) {
                    $q->where('user_id', Auth::id());
                });
            } else {
                // 未認証の場合は何も表示しない
                $items = collect();
                return view('items.index', compact('items', 'keyword', 'page'));
            }
        } else {
            // FN014: 全商品表示（自分が出品した商品は除外）
            if (Auth::check()) {
                $query->where('user_id', '!=', Auth::id());
            }
        }

        $items = $query->get();

        return view('items.index', compact('items', 'keyword', 'page'));
    }
}