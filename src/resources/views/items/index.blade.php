<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品一覧</title>
</head>
<body>
    <header>
        <a href="{{ route('items.index') }}">COACHTECH</a>

        <form action="{{ route('items.index') }}" method="GET">
            <input
                type="text"
                name="keyword"
                value="{{ request('keyword') }}"
                placeholder="なにをお探しですか？"
            >
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
        <h1>商品一覧</h1>

        @if (session('message'))
            <p>{{ session('message') }}</p>
        @endif

        <div class="tab-links">
            <a href="{{ route('items.index') }}">おすすめ</a>
            <a href="{{ route('items.index', ['tab' => 'mylist']) }}">マイリスト</a>
        </div>

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

    header a {
        margin-right: 16px;
    }

    main {
        padding: 24px;
    }

    .tab-links {
        margin-bottom: 16px;
    }

    .tab-links a {
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