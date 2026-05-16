<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;
use App\Models\Like;
use App\Models\Category;
use App\Http\Requests\ExhibitRequest;
use Illuminate\Support\Facades\Storage;

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

    public function show($item_id)
    {
        // リレーションをロードして取得
        $item = Item::with(['categories', 'comments.user'])->withCount(['likes', 'comments'])->findOrFail($item_id);

        return view('items.show', compact('item'));
    }

    /**
     * FN018: いいね機能（ログイン必須）
     */
    public function toggleLike($item_id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user_id = Auth::id();

        // 既にいいねしているかチェック
        $like = Like::where('user_id', $user_id)->where('item_id', $item_id)->first();

        if ($like) {
            // 存在するなら解除（FN018-3）
            $like->delete();
        } else {
            // 存在しないなら登録（FN018-1）
            Like::create([
                'user_id' => $user_id,
                'item_id' => $item_id,
            ]);
        }

        return back(); // 元の詳細画面にリフレッシュリダイレクト
    }

    /**
     * FN020: コメント送信機能
     */
    public function storeComment(CommentRequest $request, $item_id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
            'content' => $request->input('content'),
        ]);

        return back()->with('success', 'コメントを投稿しました');
    }

    /**
     * FN028: 商品出品画面の表示
     */
    public function create()
    {
        // 画面のチェックボックス用にすべてのカテゴリを取得
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    /**
     * FN028 / FN029: 出品商品情報登録 ＆ 画像アップロード
     */
    public function store(ExhibitRequest $request)
    {
        // 1. 商品画像のアップロード（FN029）
        // storage/app/public/item_images に保存
        $path = $request->file('item_img')->store('item_images', 'public');
        $img_url = Storage::url($path); // /storage/item_images/xxx.png に変換

        // 2. 商品データの作成
        $item = Item::create([
            'user_id' => Auth::id(), // 出品者ID
            'name' => $request->name,
            'brand' => $request->brand,
            'price' => $request->price,
            'description' => $request->description,
            'condition' => $request->condition,
            'img_url' => $img_url,
            'is_sold' => false,
        ]);

        // 3. 複数選択されたカテゴリを中間テーブルに紐付け（FN028-1-2）
        $item->categories()->attach($request->input('categories'));

        // 出品完了後はトップページ（一覧）へ戻る
        return redirect()->route('index')->with('success', '商品を出品しました！');
    }

    public function destroy($item_id)
    {
        $item = Item::findOrFail($item_id);

        // セキュリティチェック：ログインユーザーがこの商品の出品者本人か確認
        if ($item->user_id !== Auth::id()) {
            return back()->with('error', '不正な操作です。自分の出品した商品以外は削除できません。');
        }

        // 既に売れてしまっている（Sold）商品はトラブル防止のため削除不可にする
        if ($item->is_sold) {
            return back()->with('error', '売却済みの商品は削除できません。');
        }

        // 1. ストレージから商品画像を削除
        if ($item->img_url && str_contains($item->img_url, '/storage/')) {
            $path = str_replace('/storage/', 'public/', $item->img_url);
            Storage::delete($path);
        }

        // 2. データベースから商品レコードを削除（cascadeにより、中間テーブルやコメントも自動削除されます）
        $item->delete();

        // 削除後はマイページ（プロフィール）へ戻る
        return redirect()->route('profile')->with('success', '出品商品を取り下げました。');
    }
}