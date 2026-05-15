<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ccc;">
        <h2>会員登録</h2>

        <form method="POST" action="/register">
            @csrf

            <!-- 1. ユーザ名 -->
            <div>
                <label>お名前</label><br>
                <input type="text" name="name" value="{{ old('name') }}" autofocus>
                @error('name')
                    <div style="color: red;">{{ $message }}</div>
                @enderror
            </div>

            <!-- 2. メールアドレス -->
            <div style="margin-top: 15px;">
                <label>メールアドレス</label><br>
                <input type="text" name="email" value="{{ old('email') }}">
                @error('email')
                    <div style="color: red;">{{ $message }}</div>
                @enderror
            </div>

            <!-- 3. パスワード -->
            <div style="margin-top: 15px;">
                <label>パスワード</label><br>
                <input type="password" name="password">
                @error('password')
                    <div style="color: red;">{{ $message }}</div>
                @enderror
            </div>

            <!-- 4. 確認用パスワード -->
            <div style="margin-top: 15px;">
                <label>パスワード（確認用）</label><br>
                <input type="password" name="password_confirmation">
                @error('password_confirmation')
                    <div style="color: red;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" style="margin-top: 20px;">登録する</button>
        </form>

        <!-- FN005: ユーザー認証動線 -->
        <div style="margin-top: 20px; text-align: center;">
            <a href="/login">ログイン画面へ</a>
        </div>
    </div>
</body>

</html>