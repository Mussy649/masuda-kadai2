<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>商品詳細</title>

    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
</head>

<body>
    <header class="site-header">
        <div class="site-header__inner detail-header__inner">
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
                @auth
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
                @else
                    <a
                        href="{{ route('login') }}"
                        class="header-nav__link"
                    >
                        ログイン
                    </a>

                    <a
                        href="{{ route('mypage.index') }}"
                        class="header-nav__link"
                    >
                        マイページ
                    </a>
                @endauth

                <a
                    href="{{ route('items.create') }}"
                    class="header-nav__sell"
                >
                    出品
                </a>
            </nav>
        </div>
    </header>

    <main class="detail-page">
        <div class="item-detail">
            <div class="item-detail__image">
                @if ($item->image_url)
                    <img
                        src="{{ $item->image_url }}"
                        alt="{{ $item->name }}"
                        class="item-detail__image-file"
                    >
                @else
                    <span class="item-detail__image-placeholder">
                        {{ $item->name }}
                    </span>
                @endif

                @if ($isPurchased)
                    <div class="sold-overlay">
                        Sold
                    </div>
                @endif
            </div>

            <div class="item-detail__content">
                <h1 class="item-detail__name">
                    {{ $item->name }}
                </h1>

                @if ($item->brand_name)
                    <p class="item-detail__brand">
                        ブランド：{{ $item->brand_name }}
                    </p>
                @endif

                <p class="item-detail__price">
                    ¥{{ number_format($item->price) }}
                    <span class="item-detail__tax">
                        （税込）
                    </span>
                </p>

                <div class="item-actions">
                    <div class="item-action">
                        @if ($isOwnItem || $isPurchased)
                            <span class="item-action__icon">
                                ♡
                            </span>
                        @else
                            @auth
                                @if ($isLiked)
                                    <form
                                        action="{{ route('likes.destroy', ['item_id' => $item->id]) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="item-action__button item-action__button--liked"
                                            aria-label="いいねを解除する"
                                        >
                                            ♥
                                        </button>
                                    </form>
                                @else
                                    <form
                                        action="{{ route('likes.store', ['item_id' => $item->id]) }}"
                                        method="POST"
                                    >
                                        @csrf

                                        <button
                                            type="submit"
                                            class="item-action__button"
                                            aria-label="いいねする"
                                        >
                                            ♡
                                        </button>
                                    </form>
                                @endif
                            @else
                                <span class="item-action__icon">
                                    ♡
                                </span>
                            @endauth
                        @endif

                        <span class="item-action__count">
                            {{ $item->likes_count }}
                        </span>
                    </div>

                    <div class="item-action">
                        <span class="item-action__icon item-action__icon--comment">
                            ♡
                        </span>

                        <span class="item-action__count">
                            {{ $item->comments_count }}
                        </span>
                    </div>
                </div>

                @if ($isPurchased)
                    <div class="sold-label">
                        Sold
                    </div>
                @elseif ($isOwnItem)
                    <div class="own-item-message">
                        自分が出品した商品です
                    </div>
                @else
                    <a
                        href="{{ route('purchase.show', ['item_id' => $item->id]) }}"
                        class="purchase-button"
                    >
                        購入手続きへ
                    </a>
                @endif

                <section class="item-section">
                    <h2 class="item-section__title">
                        商品説明
                    </h2>

                <p class="item-section__description">{{ $item->description }}</p>
                </section>

                <section class="item-section">
                    <h2 class="item-section__title">
                        商品の情報
                    </h2>

                    <dl class="item-information">
                        <div class="item-information__row">
                            <dt class="item-information__label">
                                カテゴリー
                            </dt>

                            <dd class="item-information__value">
                                <div class="category-list">
                                    @foreach ($item->categories as $category)
                                        <span class="category-label">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </dd>
                        </div>

                        <div class="item-information__row">
                            <dt class="item-information__label">
                                商品の状態
                            </dt>

                            <dd class="item-information__value">
                                {{ $item->condition->name }}
                            </dd>
                        </div>
                    </dl>
                </section>

                <section class="comment-section">
                    <h2 class="comment-section__title">
                        コメント（{{ $item->comments_count }}）
                    </h2>

                    <div class="comment-list">
                        @forelse ($item->comments as $comment)
                            <div class="comment-item">
                                <div class="comment-user">
                                    @if ($comment->user && $comment->user->profile_image)
                                        <img
                                            src="{{ asset('storage/' . $comment->user->profile_image) }}"
                                            alt="プロフィール画像"
                                            class="comment-user__image"
                                        >
                                    @else
                                        <div class="comment-user__placeholder"></div>
                                    @endif

                                    <p class="comment-user__name">
                                        {{ $comment->user ? $comment->user->name : 'ユーザー' }}
                                    </p>
                                </div>

                                <p class="comment-text">
                                    {{ $comment->comment }}
                                </p>
                            </div>
                        @empty
                            <p class="comment-list__empty">
                                まだコメントはありません。
                            </p>
                        @endforelse
                    </div>

                    @auth
                        @if (!$isOwnItem)
                            <form
                                action="{{ route('comments.store', ['item_id' => $item->id]) }}"
                                method="POST"
                                class="comment-form"
                            >
                                @csrf

                                <label
                                    for="comment"
                                    class="comment-form__label"
                                >
                                    商品へのコメント
                                </label>

                                <textarea
                                    id="comment"
                                    name="comment"
                                    class="comment-form__textarea"
                                >{{ old('comment') }}</textarea>

                                @error('comment')
                                    <p class="error-message">
                                        {{ $message }}
                                    </p>
                                @enderror

                                <button
                                    type="submit"
                                    class="comment-form__button"
                                >
                                    コメントを送信する
                                </button>
                            </form>
                        @endif
                    @else
                        <p class="comment-login-message">
                            コメントを送信するにはログインが必要です。
                        </p>
                    @endauth
                </section>
            </div>
        </div>
    </main>
</body>

</html>