<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_item_details_are_displayed()
    {
        $seller = User::factory()->create([
            'name' => '出品者ユーザー',
        ]);

        $liker = User::factory()->create();

        $commenter = User::factory()->create([
            'name' => 'コメントユーザー',
        ]);

        $conditionId = $this->createCondition(
            '目立った傷や汚れなし'
        );

        $categoryId = $this->createCategory('ファッション');

        $item = $this->createItem(
            $seller,
            $conditionId,
            '商品詳細テスト商品'
        );

        $this->attachCategory($item, $categoryId);

        DB::table('likes')->insert([
            'user_id' => $liker->id,
            'item_id' => $item->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('comments')->insert([
            'user_id' => $commenter->id,
            'item_id' => $item->id,
            'comment' => '商品詳細テスト用のコメントです。',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get(
            route('items.show', ['item_id' => $item->id])
        );

        $response->assertStatus(200);

        $response->assertViewHas(
            'item',
            function ($viewItem) use ($item) {
                return $viewItem->id === $item->id
                    && $viewItem->likes_count === 1
                    && $viewItem->comments_count === 1;
            }
        );

        $response->assertSee(
            'https://example.com/detail-item.jpg',
            false
        );
        $response->assertSee('商品詳細テスト商品');
        $response->assertSee('ブランド：テストブランド');
        $response->assertSee('¥12,345');
        $response->assertSee('商品詳細テスト用の説明です。');
        $response->assertSee('ファッション');
        $response->assertSee('目立った傷や汚れなし');
        $response->assertSee('コメントユーザー');
        $response->assertSee(
            '商品詳細テスト用のコメントです。'
        );
    }

    public function test_all_assigned_categories_are_displayed()
    {
        $seller = User::factory()->create();

        $conditionId = $this->createCondition('良好');

        $firstCategoryId = $this->createCategory(
            'ファッション'
        );

        $secondCategoryId = $this->createCategory('家電');

        $item = $this->createItem(
            $seller,
            $conditionId,
            '複数カテゴリーテスト商品'
        );

        $this->attachCategory($item, $firstCategoryId);
        $this->attachCategory($item, $secondCategoryId);

        $response = $this->get(
            route('items.show', ['item_id' => $item->id])
        );

        $response->assertStatus(200);
        $response->assertSee('複数カテゴリーテスト商品');
        $response->assertSee('ファッション');
        $response->assertSee('家電');
    }

    private function createCondition(string $name): int
    {
        return DB::table('conditions')->insertGetId([
            'name' => $name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createCategory(string $name): int
    {
        return DB::table('categories')->insertGetId([
            'name' => $name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createItem(
        User $seller,
        int $conditionId,
        string $name
    ): Item {
        return Item::create([
            'user_id' => $seller->id,
            'condition_id' => $conditionId,
            'name' => $name,
            'brand_name' => 'テストブランド',
            'price' => 12345,
            'description' => '商品詳細テスト用の説明です。',
            'image_path' => 'https://example.com/detail-item.jpg',
        ]);
    }

    private function attachCategory(
        Item $item,
        int $categoryId
    ): void {
        DB::table('category_item')->insert([
            'item_id' => $item->id,
            'category_id' => $categoryId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}