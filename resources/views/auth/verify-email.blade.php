<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メール認証 - COACHTECH</title>
</head>

<body
    style="background-color: #ffffff; min-height: 100vh; padding: 0; margin: 0; font-family: 'Helvetica Neue', Arial, sans-serif; box-sizing: border-box;">

    <header
        style="background-color: #000000; padding: 15px 40px; display: flex; align-items: center; min-height: 40px;">
        <a href="{{ route('index') }}" style="display: flex; align-items: center; text-decoration: none;">
            <img src="{{ asset('img/logo.png') }}" alt="COACHTECH" style="height: 40px; object-fit: contain;">
        </a>
    </header>

    <div style="max-width: 1300px; margin: 0 auto; width: 100%; padding: 0 20px; box-sizing: border-box; text-align: center;">

        <p style="font-size: 16px; font-weight: bold; color: #000000; line-height: 1.8; margin-bottom: 40px;">
            登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール認証を完了してください。
        </p>

        @if (session('status') == 'verification-link-sent')
            <div style="color: #137333; font-size: 13px; margin-bottom: 20px;">
                新しい認証リンクを再送信しました。
            </div>
        @endif

        <div style="margin-bottom: 40px;">
            <a href="http://localhost:8025" target="_blank"
                style="display: inline-block; background-color: #dcdcdc; color: #000000; text-decoration: none; padding: 14px 40px; border-radius: 6px; border: 1px solid #b5b5b5; font-size: 16px; font-weight: bold; cursor: pointer; transition: background-color 0.2s;">
                認証はこちらから
            </a>
        </div>

        <form method="POST" action="{{ route('verification.send') }}" style="display: inline;">
            @csrf
            <button type="submit"
                style="background: none; border: none; color: #0066cc; text-decoration: none; font-size: 13px; cursor: pointer; font-family: inherit;">
                認証メールを再送する
            </button>
        </form>

    </div>
</body>

</html>