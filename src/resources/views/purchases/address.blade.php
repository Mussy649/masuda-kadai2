<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>住所の変更</title>
    <style>
        body {
            margin: 0;
            font-family: sans-serif;
            color: #333;
        }

        .header {
            background: #000;
            padding: 12px 24px;
        }

        .header__logo {
            color: #fff;
            font-weight: bold;
            text-decoration: none;
        }

        .address {
            max-width: 600px;
            margin: 60px auto;
        }

        .address__title {
            text-align: center;
            font-size: 24px;
            margin-bottom: 40px;
        }

        .address__group {
            margin-bottom: 24px;
        }

        .address__label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .address__input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
        }

        .address__error {
            color: red;
            margin-top: 4px;
        }

                .address__button {
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
        <a href="{{ route('items.index') }}" class="header__logo">COACHTECH</a>
    </header>

    <main class="address">
    <main class="address">
        <h1 class="address__title">住所の変更</h1>

        <form action="{{ route('purchase.address.update', ['item_id' => $item->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="address__group">
                <label class="address__label">郵便番号</label>
                <input type="text" name="postal_code" class="address__input" value="{{ old('postal_code', $user->postal_code) }}">
                @error('postal_code')
                    <p class="address__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="address__group">
                <label class="address__label">住所</label>
                <input type="text" name="address" class="address__input" value="{{ old('address', $user->address) }}">
                @error('address')
                    <p class="address__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="address__group">
                <label class="address__label">建物名</label>
                <input type="text" name="building" class="address__input" value="{{ old('building', $user->building) }}">
                @error('building')
                    <p class="address__error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="address__button">更新する</button>
        </form>
    </main>
</body>
</html>