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
                <img
                    src="{{ asset('images/coachtech-logo.png') }}"
                    alt="COACHTECH"
                >
            </a>

            <nav class="header-nav">
                <form
                    action="{{ route('logout') }}"
                    method="POST"
                    class="header-nav__form"
                >
                    @csrf

                    <button
                        type="submit"
                        class="header-nav__button"
                    >
                        ログアウト
                    </button>
                </form>

                <a
                    href="{{ route('mypage.index') }}"
                    class="header-nav__link"
                >
                    マイページ
                </a>

                <a
                    href="{{ route('items.create') }}"
                    class="header-nav__sell"
                >
                    出品
                </a>
            </nav>
        </div>
    </header>

    <main class="profile-edit">
        <div class="profile-edit__inner">
            <h1 class="profile-edit__title">
                プロフィール設定
            </h1>

            @if (session('message'))
                <p class="message">
                    {{ session('message') }}
                </p>
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
                                id="profile-image-preview"
                                src="{{ asset('storage/' . $user->profile_image) }}"
                                alt="プロフィール画像"
                            >

                            <div
                                id="profile-image-placeholder"
                                class="profile-image-field__placeholder"
                                style="display: none;"
                            ></div>
                        @else
                            <img
                                id="profile-image-preview"
                                alt=""
                                style="display: none;"
                            >

                            <div
                                id="profile-image-placeholder"
                                class="profile-image-field__placeholder"
                            ></div>
                        @endif
                    </div>

                    <div class="profile-image-field__input">
                        <label
                            for="profile-image"
                            class="profile-image-button"
                        >
                            画像を選択する
                        </label>

                        <input
                            type="file"
                            name="profile_image"
                            id="profile-image"
                            class="profile-image-input"
                            accept="image/*"
                        >

                        @error('profile_image')
                            <p class="error-message">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label
                        for="name"
                        class="form-label"
                    >
                        ユーザー名
                    </label>

                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-input"
                        value="{{ old('name', $user->name) }}"
                    >

                    @error('name')
                        <p class="error-message">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="form-group">
                    <label
                        for="postal-code"
                        class="form-label"
                    >
                        郵便番号
                    </label>

                    <input
                        type="text"
                        name="postal_code"
                        id="postal-code"
                        class="form-input"
                        value="{{ old('postal_code', $user->postal_code) }}"
                    >

                    @error('postal_code')
                        <p class="error-message">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="form-group">
                    <label
                        for="address"
                        class="form-label"
                    >
                        住所
                    </label>

                    <input
                        type="text"
                        name="address"
                        id="address"
                        class="form-input"
                        value="{{ old('address', $user->address) }}"
                    >

                    @error('address')
                        <p class="error-message">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="form-group">
                    <label
                        for="building"
                        class="form-label"
                    >
                        建物名
                    </label>

                    <input
                        type="text"
                        name="building"
                        id="building"
                        class="form-input"
                        value="{{ old('building', $user->building) }}"
                    >

                    @error('building')
                        <p class="error-message">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="profile-edit-button"
                >
                    更新する
                </button>
            </form>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const imageInput = document.getElementById('profile-image');
            const imagePreview = document.getElementById(
                'profile-image-preview'
            );
            const imagePlaceholder = document.getElementById(
                'profile-image-placeholder'
            );

            if (!imageInput || !imagePreview || !imagePlaceholder) {
                return;
            }

            imageInput.addEventListener('change', function (event) {
                const file = event.target.files[0];

                if (!file) {
                    return;
                }

                const reader = new FileReader();

                reader.addEventListener('load', function (loadEvent) {
                    imagePreview.src = loadEvent.target.result;
                    imagePreview.style.display = 'block';
                    imagePlaceholder.style.display = 'none';
                });

                reader.readAsDataURL(file);
            });
        });
    </script>
</body>
</html>