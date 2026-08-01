<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ItemIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_items_are_displayed()
    {
        $seller = User::factory()->create();
        $conditionId = $this->createCondition();

        $firstItem = $this->createItem(
            $seller,
            $conditionId,
            '一覧テスト商品A'
        );

        $secondItem = $this->createItem(
            $seller,
            $conditionId,
            '一覧テスト商品B'
        );

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee($firstItem->name);
        $response->assertSee($secondItem->name);
    }

    public function test_purchased_item_is_displayed_as_sold()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $conditionId = $this->createCondition();

        $item = $this->createItem(
            $seller,
            $conditionId,
            '購入済みテスト商品'
        );

        DB::table('purchases')->insert([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'カード支払い',
            'postal_code' => '123-4567',
            'address' => '東京都テスト区1-1-1',
            'building' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee($item->name);
        $response->assertSee('Sold');
    }

    public function test_authenticated_users_own_item_is_not_displayed()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $conditionId = $this->createCondition();

        $ownItem = $this->createItem(
            $user,
            $conditionId,
            '自分の出品商品'
        );

        $otherItem = $this->createItem(
            $otherUser,
            $conditionId,
            '他人の出品商品'
        );

        $response = $this
            ->actingAs($user)
            ->get('/');

        $response->assertStatus(200);
        $response->assertDontSee($ownItem->name);
        $response->assertSee($otherItem->name);
    }

    private function createCondition(): int
    {
        return DB::table('conditions')->insertGetId([
            'name' => '良好',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createItem(
        User $user,
        int $conditionId,
        string $name
    ): Item {
        return Item::create([
            'user_id' => $user->id,
            'condition_id' => $conditionId,
            'name' => $name,
            'brand_name' => null,
            'price' => 1000,
            'description' => '商品一覧テスト用の商品です。',
            'image_path' => 'https://example.com/test-item.jpg',
        ]);
    }
}