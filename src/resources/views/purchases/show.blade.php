<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品購入</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/purchases/show.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="site-header__inner purchase-header__inner">
            <a href="{{ route('items.index') }}" class="site-logo">
                <img
                    src="{{ asset('images/coachtech-logo.png') }}"
                    alt="COACHTECH"
                >
            </a>

            <form
                action="{{ route('items.index') }}"
                method="GET"
                class="header-search"
            >
                <input
                    type="text"
                    name="keyword"
                    value="{{ request('keyword') }}"
                    placeholder="なにをお探しですか？"
                    class="header-search__input"
                >
            </form>

            <nav class="header-nav">
                <form
                    action="{{ route('logout') }}"
                    method="POST"
                    class="header-nav__form"
                >
                    @csrf
                    <button type="submit" class="header-nav__button">
                        ログアウト
                    </button>
                </form>

                <a
                    href="{{ route('mypage.index') }}"
                    class="header-nav__link"
                >
                    マイページ
                </a>

                <a href="/sell" class="header-nav__sell">
                    出品
                </a>
            </nav>
        </div>
    </header>

    <main class="purchase-page">
        <form
            action="{{ route('purchase.store', ['item_id' => $item->id]) }}"
            method="POST"
            class="purchase-form"
        >
            @csrf

            <div class="purchase-main">
                <section class="purchase-item">
                    <div class="purchase-item__image">
                        @if ($item->image_url)
                            <img
                                src="{{ $item->image_url }}"
                                alt="{{ $item->name }}"
                                class="purchase-item__image-img"
                            >
                        @else
                            {{ $item->name }}
                        @endif
                    </div>

                    <div class="purchase-item__information">
                        <h1 class="purchase-item__name">
                            {{ $item->name }}
                        </h1>

                        <p class="purchase-item__price">
                            ¥{{ number_format($item->price) }}
                        </p>
                    </div>
                </section>

                <section class="purchase-section">
                    <h2 class="purchase-section__title">
                        支払い方法
                    </h2>

                    <div class="purchase-section__content">
                        <select
                            name="payment_method"
                            id="payment_method"
                            class="purchase-select"
                        >
                            <option value="">
                                選択してください
                            </option>

                            <option
                                value="コンビニ払い"
                                {{ old('payment_method') === 'コンビニ払い' ? 'selected' : '' }}
                            >
                                コンビニ払い
                            </option>

                            <option
                                value="カード支払い"
                                {{ old('payment_method') === 'カード支払い' ? 'selected' : '' }}
                            >
                                カード支払い
                            </option>
                        </select>

                        @error('payment_method')
                            <p class="error-message">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </section>

                <section class="purchase-section purchase-address">
                    <div class="purchase-address__header">
                        <h2 class="purchase-section__title">
                            配送先
                        </h2>

                        <a
                            href="{{ route('purchase.address.edit', ['item_id' => $item->id]) }}"
                            class="purchase-address__change-link"
                        >
                            変更する
                        </a>
                    </div>

                    <div class="purchase-address__content">
                        <p class="purchase-address__postal-code">
                            〒 {{ $user->postal_code }}
                        </p>

                        <p class="purchase-address__text">
                            {{ $user->address }}
                            {{ $user->building }}
                        </p>
                    </div>
                </section>
            </div>

            <aside class="purchase-summary">
                <table class="purchase-summary__table">
                    <tr>
                        <th>商品代金</th>
                        <td>¥{{ number_format($item->price) }}</td>
                    </tr>

                    <tr>
                        <th>支払い方法</th>
                        <td id="selected-payment">
                            {{ old('payment_method') ?: '未選択' }}
                        </td>
                    </tr>
                </table>

                <button
                    type="submit"
                    class="purchase-button"
                >
                    購入する
                </button>
            </aside>
        </form>
    </main>

    <script>
        const paymentSelect = document.getElementById('payment_method');
        const selectedPayment = document.getElementById('selected-payment');

        paymentSelect.addEventListener('change', function () {
            selectedPayment.textContent = this.value || '未選択';
        });
    </script>
</body>
</html>