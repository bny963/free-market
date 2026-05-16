<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 👈 ここに追記：メール認証通知の文面を日本語＆要件通りにカスタマイズ
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('【COACHTECH】会員登録の確認（メールアドレス認証）')
                ->line('会員登録ありがとうございます！下のボタンを押して、メールアドレスの認証を完了させてください。')
                ->action('認証はこちらから', $url) // 👈 要件のボタン名に完全一致！
                ->line('もしアカウントを作成した覚えがない場合は、このメールを無視してください。');
        });
    }
}