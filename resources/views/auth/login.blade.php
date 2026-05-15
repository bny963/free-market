<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ccc;">
        <h2>ログイン</h2>

        <!-- 入力情報が誤っている場合の一括エラー表示（FN010-2） -->
        @if ($errors->has('email') && !$errors->has('email.required'))
            <!-- Fortifyの仕様上、ログイン失敗はemailのエラーとして返ってくることが多いです -->
            <div style="color: red; margin-bottom: 15px;">ログイン情報が登録されていません</div>
        @endif

        <form method="POST" action="/login">
            @csrf

            <!-- 1. メールアドレス -->
            <div>
                <label>メールアドレス</label><br>
                <input type="text" name="email" value="{{ old('email') }}" autofocus>
                @if ($errors->has('email') && str_contains($errors->first('email'), '入力してください'))
                    <div style="color: red;">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <!-- 2. パスワード -->
            <div style="margin-top: 15px;">
                <label>パスワード</label><br>
                <input type="password" name="password">
                @error('password')
                    <div style="color: red;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" style="margin-top: 20px;">ログイン</button>
        </form>

        <!-- FN011-2: 会員登録画面への動線 -->
        <div style="margin-top: 20px; text-align: center;">
            <a href="/register">会員登録画面へ</a>
        </div>
    </div>
</body>

</html>