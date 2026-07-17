<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>メール認証</title>

    <link
        rel="stylesheet"
        href="{{ asset('css/common.css') }}"
    >
    <link
        rel="stylesheet"
        href="{{ asset('css/auth/verify-email.css') }}"
    >
</head>

<body>
    <header class="site-header">
        <div class="site-header__inner verify-header__inner">
            <a
                href="{{ route('items.index') }}"
                class="site-logo"
            >
                <img
                    src="{{ asset('images/coachtech-logo.png') }}"
                    alt="COACHTECH"
                >
            </a>
        </div>
    </header>

    <main class="verify-page">
        <div class="verify-card">
            @if (session('status') === 'verification-link-sent')
                <p class="verify-card__success">
                    認証メールを再送しました。
                </p>
            @endif

            <p class="verify-card__message">
                登録していただいたメールアドレス宛に<br>
                認証メールを送付しました。
            </p>

            <p class="verify-card__message">
                メール認証を完了してください。
            </p>

            <a
                href="http://localhost:8025"
                target="_blank"
                rel="noopener noreferrer"
                class="verify-card__button"
            >
                認証はこちらから
            </a>

            <form
                action="{{ route('verification.send') }}"
                method="POST"
                class="verify-card__resend-form"
            >
                @csrf

                <button
                    type="submit"
                    class="verify-card__resend-button"
                >
                    認証メールを再送する
                </button>
            </form>
        </div>
    </main>
</body>

</html>