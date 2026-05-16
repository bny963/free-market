<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'アプリケーション' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <!-- ヘッダーエリア -->
    <header
        style="background: #333; color: #fff; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center;">
        <h1 style="margin: 0; font-size: 20px;">マイアプリ</h1>

        @auth
            <!-- ログインしている場合のみヘッダーにログアウトボタンを表示 -->
            <form method="POST" action="/logout" style="margin: 0;">
                @csrf
                <button type="submit"
                    style="background: #e74c3c; color: white; border: none; padding: 5px 10px; cursor: pointer;">
                    ログアウト
                </button>
            </form>
        @endauth
    </header>

    <!-- メインコンテンツ -->
    <main style="padding: 20px;">
        {{ $slot }}
    </main>
</body>

</html>