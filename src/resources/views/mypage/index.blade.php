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
            @if ($user->profile_image)
                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="プロフィール画像" width="100">
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

        <div>
            @forelse ($items as $item)
                <a href="{{ route('items.show', ['item_id' => $item->id]) }}" style="text-decoration: none; color: inherit;">
                    <div style="margin-bottom: 24px;">
                        <div style="width: 200px; height: 200px; background: #eeeeee; display: flex; align-items: center; justify-content: center;">
                            {{ $item->name }}
                        </div>

                        <p>{{ $item->name }}</p>
                        <p>¥{{ number_format($item->price) }}</p>
                    </div>
                </a>
            @empty
                <p>表示する商品がありません。</p>
            @endforelse
        </div>
    </main>
</body>
</html>