<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    /**
     * FN021: 商品購入画面の表示
     */
    public function showPurchase($item_id, Request $request)
    {
        $item = Item::findOrFail($item_id);

        // ログインユーザーの情報を取得
        $user = Auth::user();

        // セッションに変更後の住所があればそれを優先、なければユーザーの初期値を適用（FN024-2）
        $address_info = session("purchase_address_{$item_id}", [
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        // 現在選択されている支払い方法（デフォルトは 'card' = カード支払い）
        $payment_method = $request->query('payment_method', 'card');

        return view('purchase.index', compact('item', 'address_info', 'payment_method'));
    }

    /**
     * FN024: 送付先住所変更画面の表示
     */
    public function showAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        // 現在の住所情報を取得（セッションにあればそれ、なければ初期値）
        $address_info = session("purchase_address_{$item_id}", [
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        return view('purchase.address', compact('item', 'address_info'));
    }

    /**
     * FN024-2: 送付先住所の変更を一時保存（反映）
     */
    public function updateAddress(Request $request, $item_id)
    {
        // 簡単なバリデーション
        $request->validate([
            'postal_code' => 'required|string',
            'address' => 'required|string',
        ]);

        // アイテムごとの配送先をセッションに一時保存（FN024-2-1の布石）
        session([
            "purchase_address_{$item_id}" => [
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building,
            ]
        ]);

        // 購入手続き画面へ戻る
        return redirect()->route('purchase.show', $item_id);
    }

    /**
     * FN022 / FN023-3: Stripe決済画面への接続・セッション作成
     */
    public function checkout($item_id, Request $request)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        // 既に売り切れの場合はストップ
        if ($item->is_sold) {
            return back()->with('error', 'この商品は既に売り切れています。');
        }

        // 現在選択されている配送先を取得（セッション、なければ初期住所）
        $address_info = session("purchase_address_{$item_id}", [
            'postal_code' => $user->postal_code,
            'address'     => $user->address,
            'building'    => $user->building,
        ]);

        if (empty($address_info['postal_code']) || empty($address_info['address'])) {
            return back()->with('error', '配送先住所を設定してください。');
        }

        // 支払い方法の取得 (card または convenience)
        $payment_method_type = $request->input('payment_method', 'card');

        // Stripeの初期化
        Stripe::setApiKey(config('services.stripe.secret') ?? env('STRIPE_SECRET'));

        // 支払い方法のタイプをStripe用にマッピング
        // ※コンビニ決済（konbini）を利用する場合はStripeダッシュボード側での有効化が必要です
        $payment_types = ['card'];
        if ($payment_method_type === 'convenience') {
            $payment_types[] = 'konbini';
        }

        // Stripe Checkout セッションの作成
        $checkout_session = Session::create([
            'payment_method_types' => $payment_types,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                        'images' => [$item->img_url],
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            // 成功時、購入情報を引き継いで戻ってくるURLを指定
            'success_url' => route('purchase.success', $item_id) . '?session_id={CHECKOUT_SESSION_ID}&payment_method=' . $payment_method_type,
            'cancel_url'  => route('purchase.show', $item_id),
            // メタデータに一時的な配送先を仕込んでおく（FN024-2-1用）
            'metadata' => [
                'user_id' => $user->id,
                'item_id' => $item->id,
                'postal_code' => $address_info['postal_code'],
                'address' => $address_info['address'],
                'building' => $address_info['building'],
            ],
        ]);

        // Stripeの決済画面へリダイレクト
        return redirect($checkout_session->url, 303);
    }

    /**
     * FN022: 決済成功後の処理（購入完了とデータベース記録）
     */
    public function success($item_id, Request $request)
    {
        $stripe_session_id = $request->query('session_id');
        $payment_method = $request->query('payment_method', 'card');

        // 既にこのStripeセッションで処理済み（二重送信防止）かチェック
        $exists = Order::where('stripe_session_id', $stripe_session_id)->exists();
        
        if (!$exists && $stripe_session_id) {
            Stripe::setApiKey(config('services.stripe.secret') ?? env('STRIPE_SECRET'));
            $session = Session::retrieve($stripe_session_id);
            $meta = $session->metadata;

            // 1. Order（購入履歴）テーブルに記録（各アイテムに配送先を紐付け: FN024-2-1）
            Order::create([
                'user_id' => $meta->user_id,
                'item_id' => $meta->item_id,
                'payment_method' => $payment_method,
                'postal_code' => $meta->postal_code,
                'address' => $meta->address,
                'building' => $meta->building,
                'stripe_session_id' => $stripe_session_id,
            ]);

            // 2. Item側のステータスを「Sold」に更新（FN022-2）
            $item = Item::findOrFail($meta->item_id);
            $item->update(['is_sold' => true]);

            // 使用した一時的な配送先セッションをクリア
            session()->forget("purchase_address_{$item_id}");
        }

        // FN022-4: 購入完了後のリダイレクト先は「商品一覧画面」
        return redirect()->route('index')->with('success', '商品の購入が完了しました！');
    }
}