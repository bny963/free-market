<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ItemControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Category::create(['name' => 'メンズ']);
    }

    // --- 4 商品一覧取得 ---
    public function test_can_get_all_items_excluding_own()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $ownItem = Item::factory()->create(['user_id' => $user->id, 'name' => '私の出品物']);
        // 他人の出品物を「is_sold => true（売却済み）」として作成
        $otherItem = Item::factory()->create(['user_id' => $otherUser->id, 'name' => '他人の出品物', 'is_sold' => true]);

        $response = $this->actingAs($user)->get(route('index'));

        $response->assertStatus(200);
        $response->assertDontSee('私の出品物');
        $response->assertSee('他人の出品物');
        $response->assertSee('Sold'); // 👈【追記】購入済み商品は「Sold」と表示されること
    }

    // --- 5 マイリスト一覧取得 ---
    public function test_mylist_shows_only_liked_items()
    {
        $user = User::factory()->create();
        // 商品を最初から売却済み（'is_sold' => true）として作成
        $item = Item::factory()->create(['name' => 'いいねした商品', 'is_sold' => true]);
        Like::create(['user_id' => $user->id, 'item_id' => $item->id]);

        $response = $this->actingAs($user)->get(route('index', ['page' => 'mylist']));
        $response->assertSee('いいねした商品');
        $response->assertSee('Sold'); // 👈【追記】マイリストでも購入済み商品は「Sold」と表示されること

        // ここでログアウトを実行して、ゲスト状態を作る
        $this->flushSession();
        \Illuminate\Support\Facades\Auth::logout();

        // 未認証の場合は何も表示されない
        $guestResponse = $this->get(route('index', ['page' => 'mylist']));
        $guestResponse->assertViewHas('items', function ($items) {
            return $items->isEmpty();
        });
    }

    // --- 6 商品検索機能 ---
    public function test_item_search_by_keyword()
    {
        Item::factory()->create(['name' => 'Apple iPhone']);
        Item::factory()->create(['name' => 'Sony Xperia']);

        $response = $this->get(route('index', ['keyword' => 'iPhone']));
        $response->assertSee('Apple iPhone');
        $response->assertDontSee('Sony Xperia');
    }

    // --- 7 商品詳細情報取得 ---
    public function test_can_view_item_details_with_categories_and_comments()
    {
        $item = Item::factory()->create(['name' => '詳細テスト商品']);
        $category = Category::first();
        $item->categories()->attach($category->id);

        $response = $this->get(route('item.show', $item->id));
        $response->assertStatus(200);
        $response->assertSee('詳細テスト商品');
        $response->assertSee('メンズ');
    }

    // --- 8 いいね機能 ---
    public function test_toggle_like_functionality()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // いいね付与
        $response = $this->actingAs($user)->post(route('item.like', $item->id));
        $this->assertDatabaseHas('likes', ['user_id' => $user->id, 'item_id' => $item->id]);

        // いいね解除
        $response = $this->actingAs($user)->post(route('item.like', $item->id));
        $this->assertDatabaseMissing('likes', ['user_id' => $user->id, 'item_id' => $item->id]);
    }

    // --- 9 コメント送信機能 ---
    public function test_store_comment_validation_and_success()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ゲストは送信不可
        $this->post(route('item.comment', $item->id), ['content' => 'ハロー'])->assertRedirect(route('login'));

        // 空コメントバリデーション
        $this->actingAs($user)->post(route('item.comment', $item->id), ['content' => ''])->assertSessionHasErrors(['content']);

        // 255文字以上バリデーション
        $longComment = str_repeat('あ', 256);
        $this->actingAs($user)->post(route('item.comment', $item->id), ['content' => $longComment])->assertSessionHasErrors(['content']);

        // 正常保存
        $this->actingAs($user)->post(route('item.comment', $item->id), ['content' => '良い商品ですね'])
            ->assertRedirect();
        $this->assertDatabaseHas('comments', ['content' => '良い商品ですね']);
    }

    // --- 15 出品商品情報登録 ---
    public function test_can_register_exhibit_item()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $category = Category::first();

        \Illuminate\Support\Facades\Storage::fake('public');

        $response = $this->actingAs($user)->post(route('item.store'), [
            'name' => '新品シャツ',
            'brand' => 'ノーブランド',
            'price' => 3000,
            'description' => '着心地が良いです。',
            'condition' => '新品',
            'item_img' => UploadedFile::fake()->image('shirt.jpg'),
            'categories' => [$category->id],
        ]);

        $response->assertRedirect(route('index'));
        $this->assertDatabaseHas('items', ['name' => '新品シャツ', 'price' => 3000]);
    }
}