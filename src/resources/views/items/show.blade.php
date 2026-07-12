<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品詳細</title>
</head>
<body>
    <header>
        <a href="{{ route('items.index') }}">COACHTECH</a>

        <form action="{{ route('items.index') }}" method="GET">
            <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？">
            <button type="submit">検索</button>
        </form>

        <nav>
            @auth
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit">ログアウト</button>
                </form>

                <a href="/mypage">マイページ</a>
                <a href="/sell">出品</a>
            @else
                <a href="/login">ログイン</a>
                <a href="/register">会員登録</a>
                <a href="/sell">出品</a>
            @endauth
        </nav>
    </header>

    <main>
        <div class="item-detail">
            <div class="item-detail__image">
                @if ($item->image_url)
                    <img
                        src="{{ $item->image_url }}"
                        alt="{{ $item->name }}"
                        class="item-detail__image-img"
                    >
                @else
                    {{ $item->name }}
                @endif

                @if ($isPurchased)
                    <div class="sold-overlay">Sold</div>
                @endif
            </div>

            <div class="item-detail__content">
                <h1 class="item-detail__name">{{ $item->name }}</h1>

                @if ($item->brand_name)
                    <p>ブランド：{{ $item->brand_name }}</p>
                @endif

                <p class="item-detail__price">¥{{ number_format($item->price) }}</p>

                <div class="item-detail__icons">
                    @if ($isOwnItem || $isPurchased)
                        <span>♡ {{ $item->likes_count }}</span>
                    @else
                        @auth
                            @if ($isLiked)
                                <form action="{{ route('likes.destroy', ['item_id' => $item->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="like-button">♥ {{ $item->likes_count }}</button>
                                </form>
                            @else
                                <form action="{{ route('likes.store', ['item_id' => $item->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="like-button">♡ {{ $item->likes_count }}</button>
                                </form>
                            @endif
                        @else
                            <span>♡ {{ $item->likes_count }}</span>
                        @endauth
                    @endif

                    <span>💬 {{ $item->comments_count }}</span>
                </div>

                @if ($isPurchased)
                    <div class="sold-label">Sold</div>
                @elseif ($isOwnItem)
                    <div class="own-item-message">自分が出品した商品です</div>
                @else
                    <a href="{{ route('purchase.show', ['item_id' => $item->id]) }}" class="purchase-button">購入手続きへ</a>
                @endif

                <h2>商品説明</h2>
                <p>{{ $item->description }}</p>

                <h2>商品の情報</h2>

                <p>
                    カテゴリー：
                    @foreach ($item->categories as $category)
                        <span class="category-label">{{ $category->name }}</span>
                    @endforeach
                </p>

                <p>商品の状態：{{ $item->condition->name }}</p>

                <h2>コメント</h2>

                <div>
                    @foreach ($item->comments as $comment)
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

                            <p class="comment-text">{{ $comment->comment }}</p>
                        </div>
                    @endforeach
                </div>

                @auth
                    <form action="{{ route('comments.store', ['item_id' => $item->id]) }}" method="POST">
                        @csrf

                        <div>
                            <textarea name="comment" rows="4" cols="50" placeholder="商品へのコメントを入力してください">{{ old('comment') }}</textarea>
                        </div>

                        @error('comment')
                            <p style="color: red;">{{ $message }}</p>
                        @enderror

                        <button type="submit">コメントを送信する</button>
                    </form>
                @else
                    <p>コメントを送信するにはログインが必要です。</p>
                @endauth
            </div>
        </div>
    </main>
</body>

<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        color: #333;
    }

    header {
        padding: 12px 24px;
        border-bottom: 1px solid #ddd;
    }

    header a {
        margin-right: 16px;
    }

    .item-detail {
        display: flex;
        gap: 56px;
        max-width: 1100px;
        margin: 60px auto;
        padding: 0 24px;
    }

    .item-detail__image {
        position: relative;
        width: 420px;
        height: 420px;
        background: #eeeeee;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        overflow: hidden;
    }

    .item-detail__image-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .sold-overlay {
        position: absolute;
        top: 16px;
        left: 16px;
        padding: 8px 18px;
        background: #ff5555;
        color: #fff;
        font-weight: bold;
        font-size: 18px;
        z-index: 1;
    }

    .item-detail__content {
        flex: 1;
    }

    .item-detail__name {
        font-size: 32px;
        margin-bottom: 12px;
    }

    .item-detail__price {
        font-size: 24px;
        margin: 16px 0;
    }

    .item-detail__icons {
        display: flex;
        align-items: center;
        gap: 16px;
        margin: 16px 0;
    }

    .like-button {
        border: none;
        background: transparent;
        font-size: 20px;
        cursor: pointer;
        padding: 0;
    }

    .purchase-button {
        display: inline-block;
        margin: 16px 0;
        padding: 10px 40px;
        background: #ff5555;
        color: #fff;
        border: none;
        cursor: pointer;
        text-decoration: none;
    }

    .sold-label {
        display: inline-block;
        margin: 16px 0;
        padding: 10px 40px;
        background: #999;
        color: #fff;
        font-weight: bold;
        text-align: center;
    }

    .own-item-message {
        margin: 16px 0;
        padding: 10px 20px;
        background: #eeeeee;
        color: #333;
        text-align: center;
    }

    .category-label {
        display: inline-block;
        margin-right: 8px;
        padding: 4px 12px;
        background: #eeeeee;
        border-radius: 12px;
    }

    .comment-item {
        margin-bottom: 16px;
    }

    .comment-user {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 6px;
    }

    .comment-user__image,
    .comment-user__placeholder {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #dddddd;
        object-fit: cover;
    }

    .comment-user__name {
        margin: 0;
        font-weight: bold;
    }

    .comment-text {
        padding: 8px;
        background: #f5f5f5;
    }

    @media screen and (max-width: 768px) {
        .item-detail {
            flex-direction: column;
            margin: 32px auto;
        }

        .item-detail__image {
            width: 100%;
            max-width: 420px;
            height: 320px;
        }
    }
</style>
</html>