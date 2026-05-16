<x-layout>
    <div style="max-width: 900px; margin: 40px auto; display: flex; gap: 50px;">
        <div style="flex: 1.5;">
            <div
                style="display: flex; gap: 20px; border-bottom: 1px solid #ddd; padding-bottom: 20px; margin-bottom: 30px;">
                <img src="{{ $item->img_url }}" alt="{{ $item->name }}"
                    style="width: 120px; aspect-ratio: 1/1; object-fit: cover; border-radius: 4px;">
                <div>
                    <h2 style="font-size: 22px; margin: 0 0 10px 0;">{{ $item->name }}</h2>
                    <p style="font-size: 18px; font-weight: bold; margin: 0;">¥{{ number_format($item->price) }}</p>
                </div>
            </div>

            <div style="margin-bottom: 40px;">
                <h3 style="font-size: 16px; margin-bottom: 10px;">支払い方法</h3>
                <select id="payment-select" name="payment_method"
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">
                    <option value="card" {{ $payment_method === 'card' ? 'selected' : '' }}>カード支払い</option>
                    <option value="convenience" {{ $payment_method === 'convenience' ? 'selected' : '' }}>コンビニ支払い</option>
                </select>
            </div>

            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <h3 style="font-size: 16px; margin: 0;">配送先</h3>
                    <a href="{{ route('purchase.address', $item->id) }}"
                        style="color: #0066cc; text-decoration: none; font-size: 14px;">変更する</a>
                </div>
                <div style="background: #f9f9f9; padding: 15px; border-radius: 4px; font-size: 14px; line-height: 1.6;">
                    @if($address_info['postal_code'])
                        <p style="margin: 0;">〒{{ $address_info['postal_code'] }}</p>
                        <p style="margin: 5px 0 0 0;">{{ $address_info['address'] }} {{ $address_info['building'] }}</p>
                    @else
                        <p style="margin: 0; color: red;">配送先住所が登録されていません。上の「変更する」から入力してください。</p>
                    @endif
                </div>
            </div>
        </div>

        <div
            style="flex: 1; border: 1px solid #ddd; padding: 20px; border-radius: 4px; height: fit-content; background: #fff;">
            @if(session('error'))
                <p style="color: red; font-size: 14px; margin-bottom: 15px;">{{ session('error') }}</p>
            @endif

            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px;">
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px 0; color: #666;">商品代金</td>
                    <td style="text-align: right; padding: 10px 0; font-weight: bold;">
                        ¥{{ number_format($item->price) }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px 0; color: #666;">支払い方法</td>
                    <td id="summary-payment" style="text-align: right; padding: 10px 0; font-weight: bold;">カード支払い</td>
                </tr>
            </table>

            <form action="{{ route('purchase.checkout', $item->id) }}" method="POST" id="checkout-form">
                @csrf
                <input type="hidden" name="payment_method" id="hidden-payment-method" value="card">

                <button type="submit"
                    style="display: block; width: 100%; background: #ff4d4d; color: #fff; text-align: center; padding: 12px; border-radius: 4px; border: none; font-weight: bold; font-size: 16px; cursor: pointer;">
                    購入する
                </button>
            </form>
        </div>

        <script>
            const paymentSelect = document.getElementById('payment-select');
            const summaryPayment = document.getElementById('summary-payment');
            const hiddenPaymentMethod = document.getElementById('hidden-payment-method'); // 👈追記

            function updateSummary() {
                const selectedValue = paymentSelect.value; // 👈追記
                const selectedText = paymentSelect.options[paymentSelect.selectedIndex].text;

                summaryPayment.textContent = selectedText;
                hiddenPaymentMethod.value = selectedValue; // 👈フォームの値を同期
            }

            paymentSelect.addEventListener('change', updateSummary);
            window.addEventListener('DOMContentLoaded', updateSummary);
        </script>
</x-layout>