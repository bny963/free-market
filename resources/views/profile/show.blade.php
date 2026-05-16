<x-layout>
    <style>
        .profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            background: #ddd;
            border: 1px solid #ccc;
        }

        .edit-btn {
            border: 2px solid #ff4d4d;
            color: #ff4d4d;
            text-decoration: none;
            padding: 6px 20px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
            transition: 0.2s;
        }

        .edit-btn:hover {
            background: #ff4d4d;
            color: #fff;
        }

        .tabs {
            display: flex;
            gap: 30px;
            border-bottom: 1px solid #ddd;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .tab-item {
            text-decoration: none;
            color: #888;
            padding: 10px 0;
            font-weight: bold;
            font-size: 16px;
        }

        .tab-item.active {
            color: #ff4d4d;
            border-bottom: 2px solid #ff4d4d;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 20px;
            max-width: 800px;
            margin: 30px auto;
            padding: 0 20px;
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
            font-size: 12px;
        }
    </style>

    <div class="profile-header">
        <div class="user-info">
            <img src="{{ $user->profile_img_url ?? 'https://via.placeholder.com/100?text=No+Image' }}" alt="ユーザーアイコン"
                class="avatar">
            <h2 style="font-size: 22px; margin: 0; font-weight: bold;">{{ $user->name }}</h2>
        </div>

        <a href="{{ route('profile.edit') }}" class="edit-btn">プロフィールを編集</a>
    </div>

    <div class="tabs">
        <a href="/profile?tab=sell" class="tab-item {{ $tab === 'sell' ? 'active' : '' }}">出品した商品</a>
        <a href="/profile?tab=buy" class="tab-item {{ $tab === 'buy' ? 'active' : '' }}">購入した商品</a>
    </div>

    <div class="grid">
        @forelse($items as $item)
            <div
                style="position: relative; border: 1px solid #eee; border-radius: 4px; padding: 8px; background: #fff; box-sizing: border-box;">
                <a href="/item/{{ $item->id }}" class="card" style="display: block;">
                    @if($item->is_sold)
                        <span class="sold-badge">Sold</span>
                    @endif
                    <img src="{{ $item->img_url }}" alt="{{ $item->name }}">
                    <p style="margin: 8px 0 0 0; font-size: 14px; font-weight: bold;">{{ $item->name }}</p>
                </a>

                @if($tab === 'sell' && !$item->is_sold)
                    <form action="{{ route('item.destroy', $item->id) }}" method="POST"
                        onsubmit="return confirm('この出品を取り下げますか？（この操作は取り消せません）');" style="margin: 8px 0 0 0;">
                        @csrf
                        <button type="submit"
                            style="width: 100%; background: #666; color: #fff; border: none; padding: 6px 0; border-radius: 4px; font-size: 12px; cursor: pointer; font-weight: bold; transition: 0.2s;">
                            出品を取り下げる
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <p style="color: #888; grid-column: 1 / -1; text-align: center; padding: 40px 0;">
                {{ $tab === 'buy' ? '購入した商品はありません' : '出品した商品はありません' }}
            </p>
        @endforelse
    </div>
</x-layout>