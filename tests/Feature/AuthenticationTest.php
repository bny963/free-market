<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    // --- 1 会員登録機能 ---

    public function test_register_requires_name()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertSessionHasErrors(['name']);
    }

    public function test_register_requires_email()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_register_requires_password()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);
        $response->assertSessionHasErrors(['password']);
    }

    public function test_register_password_must_be_at_least_8_characters()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'seven7',
            'password_confirmation' => 'seven7',
        ]);
        $response->assertSessionHasErrors(['password']);
    }

    public function test_register_password_must_match_confirmation()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different_pass',
        ]);
        $response->assertSessionHasErrors(['password']);
    }

    public function test_register_success_redirects_to_profile()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
        // 新規登録直後のリダイレクト先（要件に合わせて調整してください）
        $response->assertRedirect();
    }

    // --- 2 ログイン機能 ---

    public function test_login_requires_email()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_login_requires_password()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);
        $response->assertSessionHasErrors(['password']);
    }

    public function test_login_fails_with_invalid_credentials()
    {
        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_login_success()
    {
        $user = User::create([
            'name' => '既存ユーザー',
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/mypage/profile');
    }

    // --- 3 ログアウト機能 ---

    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }
}