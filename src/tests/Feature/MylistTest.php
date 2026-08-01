<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MylistTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_liked_items_are_displayed()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $conditionId = $this->createCondition();

        $likedItem = $this->createItem(
            $seller,
            $conditionId,
            'いいね済み商品'
        );

        $unlikedItem = $this->createItem(
            $seller,
            $conditionId,
            'いいねしていない商品'
        );

        Like::create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee($likedItem->name);
        $response->assertDontSee($unlikedItem->name);
    }

    public function test_purchased_item_is_displayed_as_sold()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $conditionId = $this->createCondition();

        $item = $this->createItem(
            $seller,
            $conditionId,
            '購入済みマイリスト商品'
        );

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        DB::table('purchases')->insert([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'カード支払い',
            'postal_code' => '123-4567',
            'address' => '東京都テスト区1-1-1',
            'building' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee($item->name);
        $response->assertSee('Sold');
    }

    public function test_no_items_are_displayed_for_guest()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $conditionId = $this->createCondition();

        $item = $this->createItem(
            $seller,
            $conditionId,
            '未認証時に表示しない商品'
        );

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertDontSee($item->name);
        $response->assertSee('表示する商品がありません。');
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
            'description' => 'マイリストテスト用の商品です。',
            'image_path' => 'https://example.com/mylist-item.jpg',
        ]);
    }
}