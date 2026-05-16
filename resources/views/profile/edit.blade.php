<x-layout>
    <div style="max-width: 600px; margin: 40px auto; padding: 0 20px;">
        <h2 style="font-size: 24px; font-weight: bold; text-align: center; margin-bottom: 30px;">プロフィール設定</h2>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display: flex; align-items: center; gap: 30px; margin-bottom: 35px;">
                <img src="{{ $user->profile_img_url ?? 'https://via.placeholder.com/100?text=No+Image' }}" alt="現在の画像"
                    style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; background: #ddd; border: 1px solid #ccc;">
                <div>
                    <label
                        style="background: #fff; color: #ff4d4d; border: 2px solid #ff4d4d; padding: 6px 15px; border-radius: 4px; font-weight: bold; font-size: 14px; cursor: pointer;">
                        画像を選択する
                        <input type="file" name="profile_img" style="display: none;">
                    </label>
                    @error('profile_img')
                        <p style="color: red; font-size: 13px; margin: 5px 0 0 0;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: bold; margin-bottom: 8px;">ユーザー名</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px;">
                @error('name') <p style="color: red; font-size: 13px; margin: 5px 0 0 0;">{{ $message }}</p> @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: bold; margin-bottom: 8px;">郵便番号</label>
                <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}"
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px;"
                    placeholder="123-4567">
                @error('postal_code') <p style="color: red; font-size: 13px; margin: 5px 0 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: bold; margin-bottom: 8px;">住所</label>
                <input type="text" name="address" value="{{ old('address', $user->address) }}"
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px;"
                    placeholder="東京都渋谷区...">
                @error('address') <p style="color: red; font-size: 13px; margin: 5px 0 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 35px;">
                <label style="display: block; font-size: 14px; font-weight: bold; margin-bottom: 8px;">建物名</label>
                <input type="text" name="building" value="{{ old('building', $user->building) }}"
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px;"
                    placeholder="ビル名・部屋番号など">
                @error('building') <p style="color: red; font-size: 13px; margin: 5px 0 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                style="display: block; width: 100%; background: #ff4d4d; color: #fff; text-align: center; padding: 12px; border-radius: 4px; border: none; font-weight: bold; font-size: 16px; cursor: pointer;">
                更新する
            </button>
        </form>
    </div>
    <script>
        // ファイルが選択されたら、フォーム上の見た目（プレビュー）を即座に切り替えるスクリプト
        const fileInput = document.querySelector('input[name="profile_img"]');
        const avatarImg = document.querySelector('img[alt="現在の画像"]');

        fileInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (row) {
                    avatarImg.src = row.target.result; // 選択した画像に画面上を切り替える
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-layout>