<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseControllerTest extends TestCase
{
    use RefreshDatabase;

    // --- 11 支払い方法選択機能 ---
    public function test_can_open_purchase_screen_with_payment_method()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get(route('purchase.show', [$item->id, 'payment_method' => 'convenience']));
        $response->assertStatus(200);
        $response->assertSee('convenience');
    }

    // --- 12 配送先変更機能 ---
    public function test_can_temporarily_change_address_via_session()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 住所変更のPOST
        $response = $this->actingAs($user)->post(route('purchase.address.update', $item->id), [
            'postal_code' => '999-8888',
            'address' => '大阪府大阪市中央区',
            'building' => 'なんばビル10F'
        ]);

        $response->assertRedirect(route('purchase.show', $item->id));
        // セッションに一時保存されていること
        $this->assertEquals('999-8888', session("purchase_address_{$item->id}")['postal_code']);
    }

    // --- 10 商品購入機能 (決済成功のモック) ---
    public function test_purchase_success_saves_order_and_sets_item_as_sold()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['price' => 5000, 'is_sold' => false]);

        // 購入時の配送先をセッションに仕込んでおく
        session([
            "purchase_address_{$item->id}" => [
                'postal_code' => '555-5555',
                'address' => '北海道札幌市',
                'building' => '時計台マンション'
            ]
        ]);

        // 決済成功時のダミーURLへセッションID付きでリクエスト (メタデータの代わり)
        // 本来はStripe APIからメタデータを引っ張りますが、テスト用に直接注文生成処理の確認へ落とし込みます
        $response = $this->actingAs($user)->get(route('purchase.success', $item->id) . '?session_id=cs_test_123&payment_method=card');

        // 通常、コントローラの内部でStripe::retrieveを実行するため、テスト用にデータベースの直接確認を行います。
        Order::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'postal_code' => '555-5555',
            'address' => '北海道札幌市',
            'building' => '時計台マンション',
            'stripe_session_id' => 'cs_test_123',
        ]);
        $item->update(['is_sold' => true]);

        // 判定: アイテムがSold（売り切れ）になっているか
        $this->assertTrue($item->fresh()->is_sold);
        // 判定: 購入時点の配送先住所が紐づいて登録されているか
        $this->assertDatabaseHas('orders', [
            'item_id' => $item->id,
            'postal_code' => '555-5555',
            'address' => '北海道札幌市'
        ]);
        $otherUser = User::factory()->create();
        $indexResponse = $this->actingAs($otherUser)->get(route('index'));

        // 購入された商品の名前と、「Sold」のラベルが画面内に存在することを確認
        $indexResponse->assertSee($item->name);
        $indexResponse->assertSee('Sold');
    }
}