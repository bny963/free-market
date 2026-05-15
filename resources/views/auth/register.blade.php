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

            <div>
                <label>お名前</label><br>
                <input type="text" name="name" required autofocus>
            </div>

            <div style="margin-top: 15px;">
                <label>メールアドレス</label><br>
                <input type="email" name="email" required>
            </div>

            <div style="margin-top: 15px;">
                <label>パスワード</label><br>
                <input type="password" name="password" required>
            </div>

            <div style="margin-top: 15px;">
                <label>パスワード（確認用）</label><br>
                <input type="password" name="password_confirmation" required>
            </div>

            <button type="submit" style="margin-top: 20px;">登録する</button>
        </form>
    </div>
</body>

</html>