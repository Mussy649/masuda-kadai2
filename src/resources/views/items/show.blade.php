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
        <div style="display: flex; gap: 40px; margin: 40px;">
            <div style="width: 400px; height: 400px; background: #eeeeee; display: flex; align-items: center; justify-content: center;">
                {{ $item->name }}
            </div>

            <div>
                <h1>{{ $item->name }}</h1>

                @if ($item->brand_name)
                    <p>ブランド：{{ $item->brand_name }}</p>
                @endif

                <p>¥{{ number_format($item->price) }}</p>

            <div>
                @auth
                    @if ($isLiked)
                        <form action="{{ route('likes.destroy', ['item_id' => $item->id]) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">♥ {{ $item->likes_count }}</button>
                        </form>
                    @else
                        <form action="{{ route('likes.store', ['item_id' => $item->id]) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit">♡ {{ $item->likes_count }}</button>
                        </form>
                    @endif
                @else
                    <span>♡ {{ $item->likes_count }}</span>
                @endauth

                <span>💬 {{ $item->comments_count }}</span>
            </div>   

                <button type="button">購入手続きへ</button>

                <h2>商品説明</h2>
                <p>{{ $item->description }}</p>

                <h2>商品の情報</h2>

                <p>
                    カテゴリー：
                    @foreach ($item->categories as $category)
                        <span>{{ $category->name }}</span>
                    @endforeach
                </p>

                <p>商品の状態：{{ $item->condition->name }}</p>

                <h2>コメント</h2>

                <p>コメント機能は後続タスクで実装予定</p>
            </div>
        </div>
    </main>
</body>
</html>