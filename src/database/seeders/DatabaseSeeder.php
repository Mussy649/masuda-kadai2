<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Taro',
                'email' => 'taro@example.com',
                'email_verified_at' => $now,
                'password' => Hash::make('password'),
                'profile_image' => null,
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区1-1-1',
                'building' => 'テストビル101',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'Hanako',
                'email' => 'hanako@example.com',
                'email_verified_at' => $now,
                'password' => Hash::make('password'),
                'profile_image' => null,
                'postal_code' => '234-5678',
                'address' => '大阪府大阪市2-2-2',
                'building' => 'サンプルマンション202',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('categories')->insert([
            ['id' => 1, 'name' => 'ファッション', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => '家電', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'インテリア', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'レディース', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'name' => 'メンズ', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'name' => 'コスメ', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'name' => '本', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 8, 'name' => 'ゲーム', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 9, 'name' => 'スポーツ', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 10, 'name' => 'キッチン', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 11, 'name' => 'ハンドメイド', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 12, 'name' => 'アクセサリー', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 13, 'name' => 'おもちゃ', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 14, 'name' => 'ベビー・キッズ', 'created_at' => $now, 'updated_at' => $now],
            ]);

        DB::table('conditions')->insert([
            ['id' => 1, 'name' => '良好', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => '目立った傷や汚れなし', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'やや傷や汚れあり', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => '状態が悪い', 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('items')->insert([
            [
                'id' => 1,
                'user_id' => 1,
                'condition_id' => 1,
                'name' => '腕時計',
                'brand_name' => 'COACHTECH',
                'price' => 15000,
                'description' => 'スタイリッシュなデザインのメンズ腕時計です。',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'user_id' => 1,
                'condition_id' => 2,
                'name' => 'HDD',
                'brand_name' => 'StoragePro',
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスクです。',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'user_id' => 1,
                'condition_id' => 1,
                'name' => '玉ねぎ3束',
                'brand_name' => null,
                'price' => 300,
                'description' => '新鮮な玉ねぎ3束のセットです。',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'user_id' => 1,
                'condition_id' => 3,
                'name' => '革靴',
                'brand_name' => 'LeatherStyle',
                'price' => 4000,
                'description' => 'クラシックなデザインの革靴です。',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'user_id' => 1,
                'condition_id' => 2,
                'name' => 'ノートPC',
                'brand_name' => 'TechBook',
                'price' => 45000,
                'description' => '高性能なノートパソコンです。',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 6,
                'user_id' => 2,
                'condition_id' => 1,
                'name' => 'マイク',
                'brand_name' => 'SoundMaster',
                'price' => 8000,
                'description' => '高音質のレコーディング用マイクです。',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 7,
                'user_id' => 2,
                'condition_id' => 2,
                'name' => 'ショルダーバッグ',
                'brand_name' => 'BagWorks',
                'price' => 3500,
                'description' => '普段使いしやすいショルダーバッグです。',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 8,
                'user_id' => 2,
                'condition_id' => 1,
                'name' => 'タンブラー',
                'brand_name' => 'DrinkMate',
                'price' => 500,
                'description' => '持ち運びに便利なタンブラーです。',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 9,
                'user_id' => 2,
                'condition_id' => 2,
                'name' => 'コーヒーミル',
                'brand_name' => 'CoffeeLife',
                'price' => 4000,
                'description' => '手動タイプのコーヒーミルです。',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 10,
                'user_id' => 2,
                'condition_id' => 1,
                'name' => 'メイクセット',
                'brand_name' => 'BeautyBox',
                'price' => 2500,
                'description' => '便利なメイクアップセットです。',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('category_item')->insert([
            ['item_id' => 1, 'category_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['item_id' => 1, 'category_id' => 5, 'created_at' => $now, 'updated_at' => $now],

            ['item_id' => 2, 'category_id' => 2, 'created_at' => $now, 'updated_at' => $now],

            ['item_id' => 3, 'category_id' => 10, 'created_at' => $now, 'updated_at' => $now],

            ['item_id' => 4, 'category_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['item_id' => 4, 'category_id' => 5, 'created_at' => $now, 'updated_at' => $now],

            ['item_id' => 5, 'category_id' => 2, 'created_at' => $now, 'updated_at' => $now],

            ['item_id' => 6, 'category_id' => 2, 'created_at' => $now, 'updated_at' => $now],

            ['item_id' => 7, 'category_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['item_id' => 7, 'category_id' => 4, 'created_at' => $now, 'updated_at' => $now],

            ['item_id' => 8, 'category_id' => 10, 'created_at' => $now, 'updated_at' => $now],

            ['item_id' => 9, 'category_id' => 10, 'created_at' => $now, 'updated_at' => $now],

            ['item_id' => 10, 'category_id' => 6, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}