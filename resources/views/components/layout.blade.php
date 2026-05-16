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
    style="background: #000; color: #fff; padding: 15px 40px; display: flex; align-items: center; justify-content: space-between;">
    <div style="display: flex; align-items: center; gap: 40px; flex: 1;">
        <a href="/">
            <img src="{{ asset('img/logo.png') }}" alt="COACHTECH" style="height: 40px; width: auto;">
        </a>

        <form action="/" method="GET" style="flex: 0.7; display: flex;">
<input type="text" name="query" value="{{ request('query') }}" placeholder="なにをお探しですか？" style="
        width: 100%; 
        padding: 10px 15px; 
        background-color: #ffffff; 
        color: #000000;
        border: 1px solid #cccccc;
        border-radius: 4px; 
        box-sizing: border-box; 
        font-size: 14px;
    ">
            <input type="hidden" name="page" value="{{ request('page', 'index') }}">
        </form>
    </div>

    <nav style="display: flex; gap: 25px; align-items: center; margin-left: 20px;">
        @auth
            <form action="/logout" method="POST" style="margin: 0;">
                @csrf
                <button type="submit"
                    style="background: none; border: none; color: #fff; cursor: pointer; font-size: 16px; font-weight: 500;">ログアウト</button>
            </form>
            <a href="/profile" style="color: #fff; text-decoration: none; font-size: 16px;">マイページ</a>
            <a href="/sell"
                style="background: #fff; color: #000; padding: 6px 20px; border-radius: 4px; text-decoration: none; font-weight: bold;">出品</a>
        @else
            <a href="/login" style="color: #fff; text-decoration: none; font-size: 16px;">ログイン</a>
            <a href="/register" style="color: #fff; text-decoration: none; font-size: 16px;">会員登録</a>
            <a href="/sell"
                style="background: #fff; color: #000; padding: 6px 20px; border-radius: 4px; text-decoration: none; font-weight: bold;">出品</a>
        @endauth
    </nav>
</header>

    <!-- メインコンテンツ -->
    <main style="padding: 20px;">
        {{ $slot }}
    </main>
</body>

</html>