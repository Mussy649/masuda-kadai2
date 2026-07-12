<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>住所の変更</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/purchases/address.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="site-header__inner address-header__inner">
            <a href="{{ route('items.index') }}" class="site-logo">
                <img
                    src="{{ asset('images/coachtech-logo.png') }}"
                    alt="COACHTECH"
                >
            </a>
        </div>
    </header>

    <main class="address-page">
        <div class="address-page__inner">
            <h1 class="address-page__title">住所の変更</h1>

            <form
                action="{{ route('purchase.address.update', ['item_id' => $item->id]) }}"
                method="POST"
                class="address-form"
            >
                @csrf
                @method('PUT')

                <div class="address-form__group">
                    <label class="address-form__label">郵便番号</label>
                    <input
                        type="text"
                        name="postal_code"
                        class="address-form__input"
                        value="{{ old('postal_code', $user->postal_code) }}"
                    >
                    @error('postal_code')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="address-form__group">
                    <label class="address-form__label">住所</label>
                    <input
                        type="text"
                        name="address"
                        class="address-form__input"
                        value="{{ old('address', $user->address) }}"
                    >
                    @error('address')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="address-form__group">
                    <label class="address-form__label">建物名</label>
                    <input
                        type="text"
                        name="building"
                        class="address-form__input"
                        value="{{ old('building', $user->building) }}"
                    >
                    @error('building')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="address-form__button">更新する</button>
            </form>
        </div>
    </main>
</body>
</html>