<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>購入手続き</title>
    <style>
        body {
            margin: 0;
            font-family: sans-serif;
            color: #333;
        }

        .header {
            background: #000;
            padding: 12px 24px;
            color: #fff;
        }

        .header__inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header__logo {
            color: #fff;
            font-weight: bold;
            text-decoration: none;
        }

        .purchase {
            max-width: 1100px;
            margin: 40px auto;
            display: flex;
            gap: 60px;
        }

        .purchase__main {
            flex: 1;
        }

        .purchase__item {
            display: flex;
            gap: 24px;
            padding-bottom: 32px;
            border-bottom: 1px solid #ddd;
        }

        .purchase__image {
            width: 160px;
            height: 160px;
            background: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .purchase__item-name {
            font-size: 20px;
            font-weight: bold;
        }

        .purchase__price {
            font-size: 18px;
            margin-top: 12px;
        }

        .purchase__section {
            padding: 32px 0;
            border-bottom: 1px solid #ddd;
        }

        .purchase__section-title {
            font-weight: bold;
            margin-bottom: 16px;
        }

        .purchase__select {
            width: 240px;
            padding: 8px;
        }

        .purchase__address-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .purchase__change-link {
            color: #0073cc;
            text-decoration: none;
            font-size: 14px;
        }

        .purchase__summary {
            width: 320px;
        }

        .summary-table {
            width: 100%;
            border: 1px solid #ddd;
            border-collapse: collapse;
        }

        .summary-table th,
        .summary-table td {
            border: 1px solid #ddd;
            padding: 20px;
            text-align: center;
        }

        .purchase__button {
            width: 100%;
            margin-top: 32px;
            padding: 12px;
            background: #ff5555;
            color: #fff;
            border: none;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header__inner">
            <a href="{{ route('items.index') }}" class="header__logo">COACHTECH</a>
        </div>
    </header>

    <form action="{{ route('purchase.store', ['item_id' => $item->id]) }}" method="POST" class="purchase">
    @csrf
        <div class="purchase__main">
            <section class="purchase__item">
                <div class="purchase__image">
                    商品画像
                </div>

                <div>
                    <div class="purchase__item-name">{{ $item->name }}</div>
                    <div class="purchase__price">¥{{ number_format($item->price) }}</div>
                </div>
            </section>

            <section class="purchase__section">
                <div class="purchase__section-title">支払い方法</div>

                <select name="payment_method" id="payment_method" class="purchase__select">
                    <option value="">選択してください</option>
                    <option value="コンビニ払い">コンビニ払い</option>
                    <option value="カード支払い">カード支払い</option>
                </select>

                @error('payment_method')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
            </section>

            <section class="purchase__section">
                <div class="purchase__address-header">
                    <div class="purchase__section-title">配送先</div>
                    <a href="{{ route('purchase.address.edit', ['item_id' => $item->id]) }}" class="purchase__change-link">変更する</a>
                </div>

                <div>
                    <p>〒 {{ $user->postal_code }}</p>
                    <p>{{ $user->address }} {{ $user->building }}</p>
                </div>
            </section>
        </div>

        <aside class="purchase__summary">
            <table class="summary-table">
                <tr>
                    <th>商品代金</th>
                    <td>¥{{ number_format($item->price) }}</td>
                </tr>
                <tr>
                    <th>支払い方法</th>
                    <td id="selected-payment">未選択</td>
                </tr>
            </table>

            <button type="submit" class="purchase__button">購入する</button>
        </aside>
</form>
<script>
    const paymentSelect = document.getElementById('payment_method');
    const selectedPayment = document.getElementById('selected-payment');

    paymentSelect.addEventListener('change', function () {
        selectedPayment.textContent = this.value || '未選択';
    });
</script>
</body>
</html>





