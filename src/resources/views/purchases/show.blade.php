<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>購入手続き</title>
</head>
<body>
    <header>
        <a href="{{ route('items.index') }}">COACHTECH</a>
    </header>

    <main>
        <h1>購入手続き画面</h1>

        <div>
            <h2>{{ $item->name }}</h2>
            <p>¥{{ number_format($item->price) }}</p>
        </div>

        <p>購入画面の詳細機能は後続タスクで実装予定</p>

        <a href="{{ route('items.show', ['item_id' => $item->id]) }}">商品詳細へ戻る</a>
    </main>
</body>
</html>