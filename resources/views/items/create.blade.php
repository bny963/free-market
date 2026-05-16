<x-layout>
    <div style="max-width: 700px; margin: 40px auto; padding: 0 20px;">
        <h2 style="font-size: 26px; font-weight: bold; text-align: center; margin-bottom: 40px;">商品の出品</h2>

        <form action="{{ route('item.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="margin-bottom: 35px;">
                <label style="display: block; font-size: 16px; font-weight: bold; margin-bottom: 10px;">商品画像</label>
                <div
                    style="border: 2px dashed #ccc; padding: 40px; text-align: center; border-radius: 4px; background: #f9f9f9; position: relative;">
                    <img id="preview" src="" alt="プレビュー"
                        style="max-width: 100%; max-height: 200px; display: none; margin: 0 auto 15px auto; border-radius: 4px;">
                    <label
                        style="background: #fff; color: #ff4d4d; border: 2px solid #ff4d4d; padding: 8px 20px; border-radius: 4px; font-weight: bold; cursor: pointer; display: inline-block;">
                        画像を選択する
                        <input type="file" name="item_img" id="item_img" style="display: none;">
                    </label>
                </div>
                @error('item_img') <p style="color: red; font-size: 14px; margin: 5px 0 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <h3
                style="font-size: 18px; border-bottom: 2px solid #333; padding-bottom: 5px; margin-bottom: 25px; color: #666;">
                商品の詳細</h3>

            <div style="margin-bottom: 25px;">
                <label
                    style="display: block; font-size: 14px; font-weight: bold; margin-bottom: 10px;">カテゴリー（複数選択可）</label>
                <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                    @foreach($categories as $category)
                        <label
                            style="background: #f5f5f5; padding: 6px 15px; border-radius: 20px; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 5px; border: 1px solid #ddd;">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" {{ is_array(old('categories')) && in_array($category->id, old('categories')) ? 'checked' : '' }}>
                            {{ $category->name }}
                        </label>
                    @endforeach
                </div>
                @error('categories') <p style="color: red; font-size: 14px; margin: 5px 0 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 35px;">
                <label style="display: block; font-size: 14px; font-weight: bold; margin-bottom: 8px;">商品の状態</label>
                <select name="condition"
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">
                    <option value="">選択してください</option>
                    <option value="良好" {{ old('condition') === '良好' ? 'selected' : '' }}>良好</option>
                    <option value="目立った傷や汚れなし" {{ old('condition') === '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし
                    </option>
                    <option value="やや傷や汚れあり" {{ old('condition') === 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
                    <option value="状態が悪い" {{ old('condition') === '状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
                </select>
                @error('condition') <p style="color: red; font-size: 14px; margin: 5px 0 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <h3
                style="font-size: 18px; border-bottom: 2px solid #333; padding-bottom: 5px; margin-bottom: 25px; color: #666;">
                商品名と説明</h3>

            <div style="margin-bottom: 25px;">
                <label style="display: block; font-size: 14px; font-weight: bold; margin-bottom: 8px;">商品名</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px;">
                @error('name') <p style="color: red; font-size: 14px; margin: 5px 0 0 0;">{{ $message }}</p> @enderror
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; font-size: 14px; font-weight: bold; margin-bottom: 8px;">ブランド名</label>
                <input type="text" name="brand" value="{{ old('brand') }}"
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px;">
                @error('brand') <p style="color: red; font-size: 14px; margin: 5px 0 0 0;">{{ $message }}</p> @enderror
            </div>

            <div style="margin-bottom: 35px;">
                <label style="display: block; font-size: 14px; font-weight: bold; margin-bottom: 8px;">商品の説明</label>
                <textarea name="description" rows="6"
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px;">{{ old('description') }}</textarea>
                @error('description') <p style="color: red; font-size: 14px; margin: 5px 0 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <h3
                style="font-size: 18px; border-bottom: 2px solid #333; padding-bottom: 5px; margin-bottom: 25px; color: #666;">
                販売価格</h3>

            <div style="margin-bottom: 40px;">
                <label style="display: block; font-size: 14px; font-weight: bold; margin-bottom: 8px;">販売価格</label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 12px; top: 10px; color: #555; font-size: 14px;">¥</span>
                    <input type="number" name="price" value="{{ old('price') }}"
                        style="width: 100%; padding: 10px 10px 10px 25px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px;"
                        placeholder="0">
                </div>
                @error('price') <p style="color: red; font-size: 14px; margin: 5px 0 0 0;">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                style="display: block; width: 100%; background: #ff4d4d; color: #fff; text-align: center; padding: 14px; border-radius: 4px; border: none; font-weight: bold; font-size: 16px; cursor: pointer;">
                出品する
            </button>
        </form>
    </div>

    <script>
        const imgInput = document.getElementById('item_img');
        const preview = document.getElementById('preview');

        imgInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (row) {
                    preview.src = row.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-layout>