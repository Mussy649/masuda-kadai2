<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品の出品</title>
</head>
<body>
    <header>
        <h1>
            <a href="{{ route('items.index') }}">COACHTECH</a>
        </h1>
    </header>

    <main>
        <h2>商品の出品</h2>

        <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div>
                <label for="image">商品画像</label>
                <input type="file" name="image" id="image">
                @error('image')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <section>
                <h3>商品の詳細</h3>

                <div>
                    <p>カテゴリー</p>
                    @foreach ($categories as $category)
                        <label>
                            <input
                                type="checkbox"
                                name="category_ids[]"
                                value="{{ $category->id }}"
                                {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}
                            >
                            {{ $category->name }}
                        </label>
                    @endforeach

                    @error('category_ids')
                        <p>{{ $message }}</p>
                    @enderror
                    @error('category_ids.*')
                        <p>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="condition_id">商品の状態</label>
                    <select name="condition_id" id="condition_id">
                        <option value="">選択してください</option>
                        @foreach ($conditions as $condition)
                            <option
                                value="{{ $condition->id }}"
                                {{ old('condition_id') == $condition->id ? 'selected' : '' }}
                            >
                                {{ $condition->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('condition_id')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
            </section>

            <section>
                <h3>商品名と説明</h3>

                <div>
                    <label for="name">商品名</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}">
                    @error('name')
                        <p>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="brand_name">ブランド名</label>
                    <input type="text" name="brand_name" id="brand_name" value="{{ old('brand_name') }}">
                    @error('brand_name')
                        <p>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description">商品の説明</label>
                    <textarea name="description" id="description">{{ old('description') }}</textarea>
                    @error('description')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
            </section>

            <section>
                <h3>販売価格</h3>

                <div>
                    <label for="price">販売価格</label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}">
                    @error('price')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
            </section>

            <button type="submit">出品する</button>
        </form>
    </main>
</body>
</html>