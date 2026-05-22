<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * シナリオ1: 会員登録後、認証メールが送信される
     */
    public function test_registration_sends_verification_email()
    {
        // 1. 通知（メール送信）を擬似化（フェイク）
        Notification::fake();

        // 2. 会員登録を実行
        $response = $this->post(route('register'), [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 登録後のリダイレクト先（通常は認証誘導画面など）を検証
        $response->assertRedirect();

        // 3. データベースにユーザーが登録されたか確認
        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);

        // 4. 登録したメールアドレス宛に認証メール（VerifyEmail通知）が送信されているか検証
        Notification::assertSentTo(
            $user,
            VerifyEmail::class
        );
    }

    /**
     * シナリオ2: メール認証誘導画面から認証サイト（リンク）に遷移する
     */
    public function test_notice_screen_contains_verification_link_or_action()
    {
        // メール未認証のユーザーを作成
        $user = User::factory()->unverified()->create();

        // 1. メール認証導線画面（案内画面）を表示する
        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertStatus(200);

        // 2 & 3. 通常、ボタンはメール再送ボタン、または「メールを確認してください」という文言になります。
        // ここでは「認証はこちらから」に類する再送フォームや案内が存在することを確認します。
        $response->assertSee('認証');
    }

    /**
     * シナリオ3: メール認証サイトのメール認証を完了すると、プロフィール設定画面に遷移する
     */
    public function test_email_can_be_verified_and_redirects_to_profile_edit()
    {
        // メール未認証のユーザーを作成
        $user = User::factory()->unverified()->create();

        // 1. 本来メールに記載される「期限付きの署名付きURL（認証リンク）」を擬似的に生成
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // 2. 生成した認証URL（メール認証サイト相当）にアクセスして認証を完了する
        $response = $this->actingAs($user)->get($verificationUrl);

        // 3. プロフィール設定画面
        $response->assertRedirect('/mypage/profile?verified=1');

        // 4. ユーザーの email_verified_at に日付が入り、認証済みになったか確認
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }
}