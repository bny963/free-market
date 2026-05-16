<x-layout>
    <div style="max-width: 500px; margin: 50px auto;">
        <h2 style="font-size: 24px; text-align: center; margin-bottom: 30px;">住所の変更</h2>

        <form action="{{ route('purchase.address.update', $item->id) }}" method="POST">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; margin-bottom: 5px; font-weight: bold;">郵便番号</label>
                <input type="text" name="postal_code" value="{{ old('postal_code', $address_info['postal_code']) }}"
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;"
                    placeholder="123-4567">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; margin-bottom: 5px; font-weight: bold;">住所</label>
                <input type="text" name="address" value="{{ old('address', $address_info['address']) }}"
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;"
                    placeholder="東京都渋谷区...">
            </div>

            <div style="margin-bottom: 30px;">
                <label style="display: block; font-size: 14px; margin-bottom: 5px; font-weight: bold;">建物名</label>
                <input type="text" name="building" value="{{ old('building', $address_info['building']) }}"
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;"
                    placeholder="ビル名・部屋番号など">
            </div>

            <button type="submit"
                style="display: block; width: 100%; background: #ff4d4d; color: #fff; text-align: center; padding: 12px; border-radius: 4px; border: none; font-weight: bold; font-size: 16px; cursor: pointer;">
                更新する
            </button>
        </form>
    </div>
</x-layout>