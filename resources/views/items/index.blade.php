<x-layout>
    <style>
        .tabs {
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
            display: flex;
            gap: 30px;
        }

        .tab-item {
            text-decoration: none;
            color: #888;
            padding: 10px 0;
            font-weight: bold;
        }

        .tab-item.active {
            color: #ff4d4d;
            border-bottom: 2px solid #ff4d4d;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .card {
            text-decoration: none;
            color: #333;
            position: relative;
        }

        .card img {
            width: 100%;
            aspect-ratio: 1/1;
            object-fit: cover;
            border-radius: 4px;
        }

        .sold-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(255, 0, 0, 0.8);
            color: #fff;
            padding: 2px 10px;
            border-radius: 20px;
            font-weight: bold;
        }
    </style>

    <div class="tabs">
        <a href="/?page=index&keyword={{ $keyword }}" class="tab-item {{ $page === 'index' ? 'active' : '' }}">おすすめ</a>
        <a href="/?page=mylist&keyword={{ $keyword }}"
            class="tab-item {{ $page === 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>

    <div class="grid">
        @forelse($items as $item)
            <a href="/item/{{ $item->id }}" class="card">
                @if($item->is_sold)
                    <span class="sold-badge">Sold</span>
                @endif
                <img src="{{ $item->img_url }}" alt="{{ $item->name }}">
                <p style="margin: 8px 0;">{{ $item->name }}</p>
            </a>
        @empty
            <p>該当する商品がありません</p>
        @endforelse
    </div>
</x-layout>