<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    // --- 13 ユーザー情報取得 ---
    public function test_can_view_profile_page_with_items()
    {
        $user = User::factory()->create(['name' => 'マイページ太郎']);
        $item = Item::factory()->create(['user_id' => $user->id, 'name' => '私が出品した本']);

        $response = $this->actingAs($user)->get(route('profile'));

        $response->assertStatus(200);
        $response->assertSee('マイページ太郎');
        $response->assertSee('私が出品した本');
    }

    // --- 14 ユーザー情報変更 ---
    public function test_profile_edit_screen_has_old_values()
    {
        $user = User::factory()->create([
            'name' => '山田二郎',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertSee('山田二郎');
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区');
    }
}