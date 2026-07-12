<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プロフィール設定</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mypage/edit.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="site-header__inner profile-edit-header__inner">
            <a href="{{ route('items.index') }}" class="site-logo">
                <img src="{{ asset('images/coachtech-logo.png') }}" alt="COACHTECH">
            </a>

            <nav class="header-nav">
                <form action="{{ route('logout') }}" method="POST" class="header-nav__form">
                    @csrf
                    <button type="submit" class="header-nav__button">ログアウト</button>
                </form>

                <a href="{{ route('mypage.index') }}" class="header-nav__link">マイページ</a>
                <a href="/sell" class="header-nav__sell">出品</a>
            </nav>
        </div>
    </header>

    <main class="profile-edit">
        <div class="profile-edit__inner">
            <h1 class="profile-edit__title">プロフィール設定</h1>

            @if (session('message'))
                <p class="message">{{ session('message') }}</p>
            @endif

            <form
                action="{{ route('mypage.profile.update') }}"
                method="POST"
                enctype="multipart/form-data"
                class="profile-edit-form"
                novalidate
            >
                @csrf
                @method('PATCH')

                <div class="profile-image-field">
                    <div class="profile-image-field__preview">
                        @if ($user->profile_image)
                            <img
                                src="{{ asset('storage/' . $user->profile_image) }}"
                                alt="プロフィール画像"
                            >
                        @else
                            <div class="profile-image-field__placeholder"></div>
                        @endif
                    </div>

                    <div class="profile-image-field__input">
                        <label for="profile_image" class="profile-image-button">
                            画像を選択する
                        </label>
                        <input
                            type="file"
                            name="profile_image"
                            id="profile_image"
                            class="profile-image-input"
                        >

                        @error('profile_image')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">ユーザー名</label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-input"
                        value="{{ old('name', $user->name) }}"
                    >
                    @error('name')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="postal_code" class="form-label">郵便番号</label>
                    <input
                        type="text"
                        name="postal_code"
                        id="postal_code"
                        class="form-input"
                        value="{{ old('postal_code', $user->postal_code) }}"
                    >
                    @error('postal_code')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="address" class="form-label">住所</label>
                    <input
                        type="text"
                        name="address"
                        id="address"
                        class="form-input"
                        value="{{ old('address', $user->address) }}"
                    >
                    @error('address')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="building" class="form-label">建物名</label>
                    <input
                        type="text"
                        name="building"
                        id="building"
                        class="form-input"
                        value="{{ old('building', $user->building) }}"
                    >
                    @error('building')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="profile-edit-button">更新する</button>
            </form>
        </div>
    </main>
</body>
</html>