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

        <div>
            <p>{{ $user->name }}</p>
            <a href="{{ route('profile.edit') }}">プロフィールを編集</a>
        </div>

        <hr>

        <div>
            <a href="{{ route('mypage.index', ['page' => 'sell']) }}">出品した商品</a>
            <a href="{{ route('mypage.index', ['page' => 'buy']) }}">購入した商品</a>
        </div>

        <hr>

        <div>
            @foreach ($items as $item)
                <a href="{{ route('items.show', ['item_id' => $item->id]) }}" style="text-decoration: none; color: inherit;">
                    <div style="margin-bottom: 24px;">
                        <div style="width: 200px; height: 200px; background: #eeeeee; display: flex; align-items: center; justify-content: center;">
                            {{ $item->name }}
                        </div>

                        <p>{{ $item->name }}</p>
                        <p>¥{{ number_format($item->price) }}</p>
                    </div>
                </a>
            @endforeach

            @if ($items->isEmpty())
                <p>表示する商品がありません。</p>
            @endif
        </div>
    </main>
</body>
</html>