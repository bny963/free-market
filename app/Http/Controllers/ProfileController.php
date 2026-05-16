<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * FN025: ユーザー情報・マイページ取得
     */
    public function show(Request $request)
    {
        $user = Auth::user();

        // 表示するタブの切り替え（デフォルトは 'sell' = 出品した商品）
        $page = $request->query('page', 'sell');

        if ($page === 'buy') {
            // FN025-4: 購入した商品一覧（Orderから商品情報を引っ張る）
            $orders = Order::with('item')->where('user_id', $user->id)->latest()->get();
            // コレクションからアイテムだけを抽出
            $items = $orders->pluck('item')->filter();
        } else {
            // FN025-3: 自分が出品した商品一覧
            $items = Item::where('user_id', $user->id)->latest()->get();
        }

        return view('profile.show', compact('user', 'items', 'page'));
    }

    /**
     * FN027: プロフィール編集画面の表示
     */
    public function edit()
    {
        // 現在ログイン中のユーザー情報を取得（過去設定されていた値が初期値になる）
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * FN027: ユーザー情報変更処理
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // バリデーションルール
        $request->validate([
            'name' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
            'profile_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 最大2MB
        ]);

        // 基本情報の更新データをセット
        $data = [
            'name' => $request->name,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ];

        // 1. プロフィール画像のアップロード処理
        if ($request->hasFile('profile_img')) {
            // 古い画像があればストレージから削除（クリーンアップ）
            if ($user->profile_img_url && str_contains($user->profile_img_url, '/storage/')) {
                $oldPath = str_replace('/storage/', 'public/', $user->profile_img_url);
                Storage::delete($oldPath);
            }

            // 新しい画像を storage/app/public/profile_images に保存
            $path = $request->file('profile_img')->store('profile_images', 'public');

            // ブラウザからアクセス可能なURL（/storage/profile_images/xxx.jpg）に変換して保存
            $data['profile_img_url'] = Storage::url($path);
        }

        // ユーザー情報の更新
        $user->update($data);

        // マイページに戻り、成功メッセージを表示
        return redirect()->route('profile')->with('success', 'プロフィールを更新しました！');
    }
}