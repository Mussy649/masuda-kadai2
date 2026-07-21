<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mypage/index.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="site-header__inner mypage-header__inner">
            <a href="{{ route('items.index') }}" class="site-logo">
                <img src="{{ asset('images/coachtech-logo.png') }}" alt="COACHTECH">
            </a>

            <form action="{{ route('items.index') }}" method="GET" class="header-search">
                <input
                    type="text"
                    name="keyword"
                    value="{{ request('keyword') }}"
                    placeholder="なにをお探しですか？"
                    class="header-search__input"
                >
            </form>

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

    <main class="mypage">
        @if (session('message'))
            <p class="message">{{ session('message') }}</p>
        @endif

        <section class="profile">
            <div class="profile__image">
                @if ($user->profile_image)
                    <img
                        src="{{ asset('storage/' . $user->profile_image) }}"
                        alt="プロフィール画像"
                    >
                @else
                    <div class="profile__placeholder"></div>
                @endif
            </div>

            <div class="profile__content">
                <p class="profile__name">{{ $user->name }}</p>
            </div>

            <a href="{{ route('mypage.profile.edit') }}" class="profile__edit-button">
                プロフィールを編集
            </a>
        </section>

        <nav class="mypage-tabs">
            <a
                href="{{ route('mypage.index', ['page' => 'sell']) }}"
                class="mypage-tabs__item {{ $page !== 'buy' ? 'is-active' : '' }}"
            >
                出品した商品
            </a>

            <a
                href="{{ route('mypage.index', ['page' => 'buy']) }}"
                class="mypage-tabs__item {{ $page === 'buy' ? 'is-active' : '' }}"
            >
                購入した商品
            </a>
        </nav>

        <section class="item-list">
            @forelse ($items as $item)
                <a
                    href="{{ route('items.show', ['item_id' => $item->id]) }}"
                    class="item-card"
                >
                    <div class="item-card__image">
                        @if ($item->image_url)
                            <img
                                src="{{ $item->image_url }}"
                                alt="{{ $item->name }}"
                                class="item-card__image-img"
                            >
                        @else
                            {{ $item->name }}
                        @endif

                        @if ($item->purchase)
                            <div class="sold-overlay">Sold</div>
                        @endif
                    </div>

                    <p class="item-card__name">{{ $item->name }}</p>
                </a>
            @empty
                <p class="item-list__empty">表示する商品がありません。</p>
            @endforelse
        </section>
    </main>
</body>
</html>