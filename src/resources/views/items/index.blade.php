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
        <h1>商品一覧</h1>

        <div>
            <a href="{{ route('items.index') }}">おすすめ</a>
            <a href="{{ route('items.index', ['tab' => 'mylist']) }}">マイリスト</a>
        </div>

        <hr>

        <div>
            @foreach ($items as $item)
    <a href="{{ route('items.show', ['item_id' => $item->id]) }}" style="text-decoration: none; color: inherit;">
        <div style="width: 200px; height: 200px; background: #eeeeee; display: flex; align-items: center; justify-content: center;">
            {{ $item->name }}
        </div>

        <p>{{ $item->name }}</p>
        <p>¥{{ number_format($item->price) }}</p>
    </a>
                </div>
            @endforeach
        </div>
    </main>
</body>
</html>