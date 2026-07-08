<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>マイページ</title>
</head>
<body>
    <header>
        <a href="{{ route('items.index') }}">COACHTECH</a>
    </header>

    <main>
        <h1>マイページ</h1>

        @if (session('message'))
            <p style="color: green;">{{ session('message') }}</p>
        @endif

        <div>
            @if ($user->profile_image)
                <img
                    src="{{ asset('storage/' . $user->profile_image) }}"
                    alt="プロフィール画像"
                    width="100"
                    height="100"
                    style="border-radius: 50%; object-fit: cover;"
                >
            @else
                <div style="width: 100px; height: 100px; background: #eeeeee; border-radius: 50%;"></div>
            @endif

            <p>{{ $user->name }}</p>

            <a href="{{ route('mypage.profile.edit') }}">プロフィールを編集</a>
        </div>

        <hr>

        <div>
            <a href="{{ route('mypage.index', ['page' => 'sell']) }}">
                出品した商品
            </a>

            <a href="{{ route('mypage.index', ['page' => 'buy']) }}">
                購入した商品
            </a>
        </div>

        @if ($page === 'buy')
            <h2>購入した商品一覧</h2>
        @else
            <h2>出品した商品一覧</h2>
        @endif

        <hr>

        <div class="item-list">
            @forelse ($items as $item)
                <a
                    href="{{ route('items.show', ['item_id' => $item->id]) }}"
                    class="item-card"
                >
                    <div class="item-image">
                        {{ $item->name }}

                        @if ($item->purchase)
                            <div class="sold-overlay">Sold</div>
                        @endif
                    </div>

                    <p>{{ $item->name }}</p>
                    <p>¥{{ number_format($item->price) }}</p>
                </a>
            @empty
                <p>表示する商品がありません。</p>
            @endforelse
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

    main {
        padding: 24px;
    }

    a {
        margin-right: 16px;
    }

    .item-list {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
    }

    .item-card {
        display: block;
        width: 200px;
        text-decoration: none;
        color: inherit;
    }

    .item-image {
        position: relative;
        width: 200px;
        height: 200px;
        background: #eeeeee;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sold-overlay {
        position: absolute;
        top: 8px;
        left: 8px;
        padding: 6px 14px;
        background: #ff5555;
        color: #fff;
        font-weight: bold;
        font-size: 14px;
    }
</style>
</html>