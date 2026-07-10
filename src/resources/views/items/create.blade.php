<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品の出品</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/items/create.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="site-header__inner sell-header__inner">
            <a href="{{ route('items.index') }}" class="site-logo">
                <img
                    src="{{ asset('images/coachtech-logo.png') }}"
                    alt="COACHTECH"
                >
            </a>

            <form
                action="{{ route('items.index') }}"
                method="GET"
                class="header-search"
            >
                <input
                    type="text"
                    name="keyword"
                    value="{{ request('keyword') }}"
                    placeholder="なにをお探しですか？"
                    class="header-search__input"
                >
            </form>

            <nav class="header-nav">
                <form
                    action="{{ route('logout') }}"
                    method="POST"
                    class="header-nav__form"
                >
                    @csrf

                    <button type="submit" class="header-nav__button">
                        ログアウト
                    </button>
                </form>

                <a
                    href="{{ route('mypage.index') }}"
                    class="header-nav__link"
                >
                    マイページ
                </a>

                <a
                    href="{{ route('items.create') }}"
                    class="header-nav__sell"
                >
                    出品
                </a>
            </nav>
        </div>
    </header>

    <main class="sell-page">
        <div class="sell-page__inner">
            <h1 class="sell-page__title">商品の出品</h1>

            <form
                action="{{ route('items.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="sell-form"
                novalidate
            >
                @csrf

                @error('item')
                    <p class="error-message sell-form__general-error">
                        {{ $message }}
                    </p>
                @enderror

                <section class="sell-section">
                    <h2 class="sell-section__title">商品画像</h2>

                    <div class="image-upload">
                        <div class="image-upload__area">
                            <img
                                id="preview-image"
                                class="image-upload__preview-image"
                                src=""
                                alt="選択した商品画像"
                            >

                            <label
                                for="image"
                                class="image-upload__button"
                            >
                                画像を選択する
                            </label>
                        </div>

                        <input
                            type="file"
                            name="image"
                            id="image"
                            class="image-upload__input"
                            accept=".jpg,.jpeg,.png"
                        >

                        @error('image')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                <section class="sell-section">
                    <h2 class="sell-section__heading">商品の詳細</h2>

                    <div class="form-group">
                        <p class="form-label">カテゴリー</p>

                        <div class="category-list">
                            @foreach ($categories as $category)
                                <label class="category-option">
                                    <input
                                        type="checkbox"
                                        name="category_ids[]"
                                        value="{{ $category->id }}"
                                        class="category-option__input"
                                        {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}
                                    >

                                    <span class="category-option__label">
                                        {{ $category->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        @error('category_ids')
                            <p class="error-message">{{ $message }}</p>
                        @enderror

                        @error('category_ids.*')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    @php
                        $selectedConditionId = old('condition_id');

                        $selectedCondition = $conditions->first(function ($condition) use ($selectedConditionId) {
                            return (string) $condition->id === (string) $selectedConditionId;
                        });
                    @endphp

                    <div class="form-group">
                        <p class="form-label">商品の状態</p>

                        <div class="custom-select" id="condition-select">
                            <input
                                type="hidden"
                                name="condition_id"
                                id="condition_id"
                                value="{{ old('condition_id') }}"
                            >

                            <button
                                type="button"
                                class="custom-select__trigger"
                                id="condition-trigger"
                                aria-haspopup="listbox"
                                aria-expanded="false"
                            >
                                <span id="condition-selected-text">
                                    {{ $selectedCondition ? $selectedCondition->name : '選択してください' }}
                                </span>

                                <span class="custom-select__arrow"></span>
                            </button>

                            <div
                                class="custom-select__menu"
                                id="condition-menu"
                                role="listbox"
                            >
                                @foreach ($conditions as $condition)
                                    <button
                                        type="button"
                                        class="custom-select__option {{ (string) old('condition_id') === (string) $condition->id ? 'is-selected' : '' }}"
                                        data-value="{{ $condition->id }}"
                                        data-label="{{ $condition->name }}"
                                        role="option"
                                        aria-selected="{{ (string) old('condition_id') === (string) $condition->id ? 'true' : 'false' }}"
                                    >
                                        {{ $condition->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        @error('condition_id')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                <section class="sell-section">
                    <h2 class="sell-section__heading">商品名と説明</h2>

                    <div class="form-group">
                        <label for="name" class="form-label">
                            商品名
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-input"
                            value="{{ old('name') }}"
                        >

                        @error('name')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="brand_name" class="form-label">
                            ブランド名
                        </label>

                        <input
                            type="text"
                            name="brand_name"
                            id="brand_name"
                            class="form-input"
                            value="{{ old('brand_name') }}"
                        >

                        @error('brand_name')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">
                            商品の説明
                        </label>

                        <textarea
                            name="description"
                            id="description"
                            class="form-textarea"
                        >{{ old('description') }}</textarea>

                        @error('description')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                <section class="sell-section sell-section--price">
                    <h2 class="sell-section__heading">販売価格</h2>

                    <div class="form-group form-group--price">
                        <div class="price-input">
                            <span class="price-input__symbol">¥</span>

                            <input
                                type="number"
                                name="price"
                                id="price"
                                class="form-input price-input__field"
                                value="{{ old('price') }}"
                                min="1"
                            >
                        </div>

                        @error('price')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                <button type="submit" class="sell-button">
                    出品する
                </button>
            </form>
        </div>
    </main>

    <script>
        const imageInput = document.getElementById('image');
        const previewImage = document.getElementById('preview-image');

        imageInput.addEventListener('change', function () {
            const file = this.files[0];

            if (!file) {
                previewImage.src = '';
                previewImage.style.display = 'none';
                return;
            }

            const reader = new FileReader();

            reader.addEventListener('load', function () {
                previewImage.src = reader.result;
                previewImage.style.display = 'block';
            });

            reader.readAsDataURL(file);
        });

        const customSelect = document.getElementById('condition-select');
        const conditionTrigger = document.getElementById('condition-trigger');
        const conditionInput = document.getElementById('condition_id');
        const selectedText = document.getElementById('condition-selected-text');
        const conditionOptions = document.querySelectorAll('.custom-select__option');

        function openConditionMenu() {
            customSelect.classList.add('is-open');
            conditionTrigger.setAttribute('aria-expanded', 'true');
        }

        function closeConditionMenu() {
            customSelect.classList.remove('is-open');
            conditionTrigger.setAttribute('aria-expanded', 'false');
        }

        conditionTrigger.addEventListener('click', function () {
            if (customSelect.classList.contains('is-open')) {
                closeConditionMenu();
            } else {
                openConditionMenu();
            }
        });

        conditionOptions.forEach(function (option) {
            option.addEventListener('click', function () {
                conditionInput.value = this.dataset.value;
                selectedText.textContent = this.dataset.label;

                conditionOptions.forEach(function (item) {
                    item.classList.remove('is-selected');
                    item.setAttribute('aria-selected', 'false');
                });

                this.classList.add('is-selected');
                this.setAttribute('aria-selected', 'true');

                closeConditionMenu();
            });
        });

        document.addEventListener('click', function (event) {
            if (!customSelect.contains(event.target)) {
                closeConditionMenu();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeConditionMenu();
                conditionTrigger.focus();
            }
        });
    </script>
</body>
</html>