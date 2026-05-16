<x-layout>
    <div style="max-width: 800px; margin: 40px auto; padding: 0 20px;">
        <h2 style="font-size: 24px; margin-bottom: 20px;">マイページ</h2>
        <p style="font-size: 16px; color: #555; margin-bottom: 40px;">こんにちは、<strong>{{ Auth::user()->name }}</strong> さん
        </p>

        <h3 style="font-size: 18px; border-bottom: 2px solid #333; padding-bottom: 8px; margin-bottom: 20px;">
            購入した商品の一覧 (FN022-3)
        </h3>

        @if($orders->isEmpty())
            <p style="color: #888;">まだ購入した商品はありません。</p>
        @else
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 20px;">
                @foreach($orders as $order)
                    @if($order->item)
                        <div style="position: relative;">
                            <span
                                style="position: absolute; top: 10px; left: 10px; background: rgba(255,0,0,0.8); color: #fff; padding: 2px 8px; border-radius: 20px; font-weight: bold; font-size: 12px;">Sold</span>
                            <img src="{{ $order->item->img_url }}" alt="{{ $order->item->name }}"
                                style="width: 100%; aspect-ratio: 1/1; object-fit: cover; border-radius: 4px;">
                            <p style="margin: 8px 0 2px 0; font-weight: bold; font-size: 14px;">{{ $order->item->name }}</p>
                            <p style="margin: 0; color: #666; font-size: 13px;">購入日: {{ $order->created_at->format('Y/m/d') }}</p>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</x-layout>