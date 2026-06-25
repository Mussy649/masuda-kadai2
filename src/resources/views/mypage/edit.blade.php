<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>プロフィール設定</title>
</head>
<body>
    <header>
        <a href="/">COACHTECH</a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">ログアウト</button>
        </form>
    </header>

    <main>
        <h1>プロフィール設定</h1>

        @if (session('message'))
            <p>{{ session('message') }}</p>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div>
                <label for="profile_image">プロフィール画像</label>
                <input type="file" name="profile_image" id="profile_image">
                @error('profile_image')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name">ユーザー名</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}">
                @error('name')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="postal_code">郵便番号</label>
                <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
                @error('postal_code')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="address">住所</label>
                <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}">
                @error('address')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="building">建物名</label>
                <input type="text" name="building" id="building" value="{{ old('building', $user->building) }}">
                @error('building')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <button type="submit">更新する</button>
        </form>
    </main>
</body>
</html>
