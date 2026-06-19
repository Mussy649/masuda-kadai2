# アプリケーション名

新規模擬案件\_フリマアプリ

## 概要

フリマアプリを想定したLaravelアプリケーションです。

ユーザーは会員登録・ログインを行い、商品一覧や商品詳細を閲覧できます。  
ログイン後は、商品の出品、購入、いいね、コメント投稿、プロフィール設定・編集などを行えます。

未ログイン状態でも商品一覧・商品詳細の閲覧はできますが、いいね、コメント、購入、出品、プロフィール関連機能はログイン後に利用する仕様です。

## 実装機能

- 会員登録機能
- ログイン機能
- ログアウト機能
- メール認証機能
- プロフィール設定機能
- プロフィール編集機能
- 商品一覧表示機能
- 商品検索機能
- マイリスト表示機能
- 商品詳細表示機能
- いいね機能
- コメント投稿機能
- 商品購入機能
- 支払い方法選択機能
- 送付先住所変更機能
- 購入済み商品表示機能
- 商品出品機能
- 商品画像アップロード機能

## 環境構築

### 1. リポジトリからダウンロード

```bash
git clone git@github.com:Mussy649/masuda-kadai2.git
```

```bash
cd masuda-kadai2
```

### 2. Dockerコンテナを構築・起動

```bash
docker compose up -d --build
```

※環境によっては以下のコマンドを使用してください。

```bash
docker-compose up -d --build
```

### 3. phpコンテナにログイン

```bash
docker compose exec php bash
```

※環境によっては以下のコマンドを使用してください。

```bash
docker-compose exec php bash
```

### 4. Composer依存パッケージをインストール

```bash
composer install
```

### 5. 「.env.example」をコピーして「.env」を作成

```bash
cp .env.example .env
```

### 6. .envファイルのDB設定を変更

`.env` ファイル内の該当箇所を以下のように変更します。

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

### 7. アプリケーションキーを作成

```bash
php artisan key:generate
```

### 8. DBのテーブルを作成

```bash
php artisan migrate
```

### 9. DBのテーブルに初期データを投入

```bash
php artisan db:seed
```

### 10. ストレージリンクを作成

```bash
php artisan storage:link
```

### 11. キャッシュをクリア

```bash
php artisan optimize:clear
```

## 使用技術

- PHP 8.1
- Laravel 8.x
- Laravel Fortify
- MySQL 8.0
- nginx
- Docker / Docker Compose
- phpMyAdmin

## URL

- 商品一覧画面：http://localhost/
- 会員登録画面：http://localhost/register
- ログイン画面：http://localhost/login
- プロフィール画面：http://localhost/mypage
- 商品出品画面：http://localhost/sell
- phpMyAdmin：http://localhost:8080

## ER図

![ER図](ER.drawio.png)

## 補足

本アプリは、模擬案件として作成するフリマアプリです。

画面構成はFigmaの参考UIを基準にし、指定された要件に沿って実装しています。

商品画像やプロフィール画像はstorageに保存し、DBには画像パスを保存する方針です。

データ管理は、主に以下の8テーブルで構成予定です。

```text
users
items
categories
category_item
conditions
likes
comments
purchases
```

購入時の送付先住所は、ユーザーの基本住所とは分けて、購入ごとの送付先情報として管理する方針です。

※実装の進行に合わせて、READMEの内容は提出前に最終更新します。
