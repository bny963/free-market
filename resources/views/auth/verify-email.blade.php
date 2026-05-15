<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>メール認証</title>
</head>

<body>
    <div style="max-width: 500px; margin: 50px auto; text-align: center;">
        <h2>メール認証が必要です</h2>
        <p>登録いただいたメールアドレスに認証リンクを送信しました。</p>

        @if (session('status') == 'verification-link-sent')
            <div style="color: green; margin-bottom: 20px;">
                新しい認証メールを再送信しました。
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit">認証メールを再送する</button>
        </form>

        <div style="margin-top: 30px;">
            <p>メールが届かない場合や、確認が完了したら</p>
            <a href="http://localhost:8025" target="_blank"
                style="display: inline-block; padding: 10px; background: #eee;">
                メールボックスを確認する (Mailpit)
            </a>
        </div>
    </div>
</body>

</html>