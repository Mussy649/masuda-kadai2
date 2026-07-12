<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品一覧</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="site-header__inner site-header__inner--items">
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
                @auth
                    <form action="{{ route('logout') }}" method="POST" class="header-nav__form">
                        @csrf
                        <button type="submit" class="header-nav__button">ログアウト</button>
                    </form>

                    <a href="/mypage" class="header-nav__link">マイページ</a>
                    <a href="/sell" class="header-nav__sell">出品</a>
                @else
                    <a href="/login" class="header-nav__link">ログイン</a>
                    <a href="/register" class="header-nav__link">マイページ</a>
                    <a href="/sell" class="header-nav__sell">出品</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="item-index">
        <div class="item-index__inner">
            <h1 class="item-index__title">商品一覧</h1>

            @if (session('message'))
                <p class="message">{{ session('message') }}</p>
            @endif

            <div class="tab-links">
                <a
                    href="{{ route('items.index') }}"
                    class="tab-links__item {{ request('tab') !== 'mylist' ? 'is-active' : '' }}"
                >
                    おすすめ
                </a>

                <a
                    href="{{ route('items.index', ['tab' => 'mylist']) }}"
                    class="tab-links__item {{ request('tab') === 'mylist' ? 'is-active' : '' }}"
                >
                    マイリスト
                </a>
            </div>

            <div class="item-list">
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
                        <p class="item-card__price">¥{{ number_format($item->price) }}</p>
                    </a>
                @empty
                    <p class="item-list__empty">表示する商品がありません。</p>
                @endforelse
            </div>
        </div>
    </main>
</body>
</html>