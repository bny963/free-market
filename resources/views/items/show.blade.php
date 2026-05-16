<x-layout>
    <div style="display: flex; gap: 50px; max-width: 1000px; margin: 0 auto; padding-top: 40px;">
        <div style="flex: 1;">
            <img src="{{ $item->img_url }}" alt="{{ $item->name }}" style="width: 100%; border-radius: 8px;">
        </div>

        <div style="flex: 1;">
            <h1 style="font-size: 28px; margin-bottom: 5px;">{{ $item->name }}</h1>
            <p style="color: #666; margin-bottom: 20px;">{{ $item->brand }}</p>
            <p style="font-size: 24px; font-weight: bold; margin-bottom: 20px;">¥{{ number_format($item->price) }} (税込)
            </p>

            <div style="display: flex; gap: 30px; margin-bottom: 30px;">
                <div style="text-align: center;">
                    <form action="{{ route('item.like', $item->id) }}" method="POST" style="margin: 0;">
                        @csrf
                        @php
                            $isLiked = Auth::check() && $item->likes()->where('user_id', Auth::id())->exists();
                        @endphp
                        <button type="submit"
                            style="background: none; border: none; cursor: pointer; font-size: 28px; padding: 0; color: {{ $isLiked ? '#ff4d4d' : '#ccc' }};">
                            ★
                        </button>
                    </form>
                    <span style="font-size: 14px; color: #555;">{{ $item->likes_count }}</span>
                </div>

                <div style="text-align: center;">
                    <span style="font-size: 28px; color: #ccc; line-height: 1;">💬</span><br>
                    <span style="font-size: 14px; color: #555;">{{ $item->comments_count }}</span>
                </div>
            </div>

            <a href="/purchase/{{ $item->id }}"
                style="display: block; width: 100%; background: #ff4d4d; color: #fff; text-align: center; padding: 12px; border-radius: 4px; text-decoration: none; font-weight: bold; margin-bottom: 40px;">購入手続きへ</a>

            <h2 style="font-size: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">商品説明</h2>
            <p style="margin-top: 15px; line-height: 1.6; color: #333;">{{ $item->description }}</p>

            <h2 style="font-size: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-top: 40px;">商品の情報
            </h2>
            <table style="width: 100%; margin-top: 15px; border-collapse: collapse;">
                <tr>
                    <th style="text-align: left; padding: 10px 0; width: 30%;">カテゴリー</th>
                    <td style="padding: 10px 0;">
                        @foreach($item->categories as $category)
                            <span
                                style="background: #f0f0f0; padding: 3px 10px; border-radius: 15px; margin-right: 5px; font-size: 14px;">{{ $category->name }}</span>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left; padding: 10px 0;">商品の状態</th>
                    <td style="padding: 10px 0;">{{ $item->condition }}</td>
                </tr>
            </table>

            <h2 style="font-size: 20px; margin-top: 5px;">コメント ({{ $item->comments_count }})</h2>
            <div style="margin-top: 20px; max-height: 300px; overflow-y: auto; padding-right: 10px;">
                @foreach($item->comments as $comment)
                    <div style="margin-bottom: 20px;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 5px;">
                            <div style="width: 32px; height: 32px; background: #ddd; border-radius: 50%;"></div> <span
                                style="font-weight: bold; font-size: 14px;">{{ $comment->user->name }}</span>
                        </div>
                        <div
                            style="background: #f5f5f5; padding: 10px 15px; border-radius: 8px; font-size: 14px; line-height: 1.5;">
                            {{ $comment->content }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 30px;">
                <h3 style="font-size: 16px; margin-bottom: 10px;">商品へのコメント</h3>
                @auth
                    <form action="{{ route('item.comment', $item->id) }}" method="POST">
                        @csrf
                        <textarea name="content" rows="4"
                            style="width: 100%; border: 1px solid #ccc; border-radius: 4px; padding: 10px; font-size: 14px; box-sizing: border-box;"
                            placeholder="コメントを入力してください">{{ old('content') }}</textarea>

                        @error('content')
                            <p style="color: red; font-size: 14px; margin: 5px 0 0 0;">{{ $message }}</p>
                        @enderror

                        <button type="submit"
                            style="display: block; width: 100%; background: #333; color: #fff; text-align: center; padding: 10px; border-radius: 4px; border: none; font-weight: bold; margin-top: 10px; cursor: pointer;">コメントを送信する</button>
                    </form>
                @else
                    <p style="background: #eee; padding: 15px; text-align: center; border-radius: 4px; font-size: 14px;">
                        コメントするには<a href="/login">ログイン</a>が必要です。
                    </p>
                @endauth
            </div>
        </div>
    </div>
</x-layout>