<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="site-header__inner">
            <a href="{{ route('items.index') }}" class="site-logo">
                <img src="{{ asset('images/coachtech-logo.png') }}" alt="COACHTECH">
            </a>
        </div>
    </header>

    <main class="auth-page">
        <div class="auth-container">
            <h1 class="auth-title">ログイン</h1>

            <form action="{{ route('login') }}" method="POST" class="auth-form" novalidate>
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">メールアドレス</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-input"
                        value="{{ old('email') }}"
                    >
                    @error('email')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">パスワード</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-input"
                    >
                    @error('password')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="auth-button">ログインする</button>
            </form>

            <div class="auth-link">
                <a href="{{ route('register') }}">会員登録はこちら</a>
            </div>
        </div>
    </main>
</body>
</html>