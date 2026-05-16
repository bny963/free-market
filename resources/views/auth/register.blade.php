<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録 - COACHTECH</title>
</head>

<body
    style="background-color: #ffffff; min-height: 100vh; padding: 0; margin: 0; font-family: 'Helvetica Neue', Arial, sans-serif; box-sizing: border-box;">

    <header
        style="background-color: #000000; padding: 15px 40px; display: flex; align-items: center; min-height: 40px;">
        <a href="{{ route('index') }}" style="display: flex; align-items: center; text-decoration: none;">
            <img src="{{ asset('img/logo.png') }}" alt="COACHTECH" style="height: 40px; object-fit: contain;">
        </a>
    </header>

    <div style="max-width: 400px; margin: 80px auto; padding: 0 20px; box-sizing: border-box;">

        <h2
            style="font-size: 28px; font-weight: bold; text-align: center; margin-top: 0; margin-bottom: 40px; color: #000000; letter-spacing: 1px;">
            会員登録</h2>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div style="margin-bottom: 25px;">
                <label
                    style="display: block; font-size: 14px; font-weight: bold; margin-bottom: 8px; color: #000000;">ユーザー名</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    style="width: 100%; padding: 12px; border: 1px solid #cccccc; border-radius: 4px; box-sizing: border-box; font-size: 14px;">
                @error('name')
                    <p style="color: red; font-size: 13px; margin: 5px 0 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 25px;">
                <label
                    style="display: block; font-size: 14px; font-weight: bold; margin-bottom: 8px; color: #000000;">メールアドレス</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    style="width: 100%; padding: 12px; border: 1px solid #cccccc; border-radius: 4px; box-sizing: border-box; font-size: 14px;">
                @error('email')
                    <p style="color: red; font-size: 13px; margin: 5px 0 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 25px;">
                <label
                    style="display: block; font-size: 14px; font-weight: bold; margin-bottom: 8px; color: #000000;">パスワード</label>
                <input type="password" name="password"
                    style="width: 100%; padding: 12px; border: 1px solid #cccccc; border-radius: 4px; box-sizing: border-box; font-size: 14px;">
                @error('password')
                    <p style="color: red; font-size: 13px; margin: 5px 0 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 40px;">
                <label
                    style="display: block; font-size: 14px; font-weight: bold; margin-bottom: 8px; color: #000000;">確認用パスワード</label>
                <input type="password" name="password_confirmation"
                    style="width: 100%; padding: 12px; border: 1px solid #cccccc; border-radius: 4px; box-sizing: border-box; font-size: 14px;">
            </div>

            <button type="submit"
                style="display: block; width: 100%; background-color: #ff5252; color: #ffffff; text-align: center; padding: 14px; border-radius: 4px; border: none; font-weight: bold; font-size: 16px; cursor: pointer; margin-bottom: 25px; transition: background-color 0.2s;">
                登録する
            </button>

            <div style="text-align: center;">
                <a href="{{ route('login') }}"
                    style="color: #0066cc; text-decoration: none; font-size: 14px;">ログインはこちら</a>
            </div>
        </form>

    </div>
</body>

</html>