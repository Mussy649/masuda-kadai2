<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_card_payment_success_saves_purchase()
    {
        $buyer = $this->createBuyer();

        $item = $this->createPurchasableItem(
            '購入完了テスト商品'
        );

        $response = $this
            ->actingAs($buyer)
            ->get(
                route('purchase.success', [
                    'item_id' => $item->id,
                    'payment_method' => 'カード支払い',
                ])
            );

        $response->assertRedirect(
            route('mypage.index', ['page' => 'buy'])
        );

        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'カード支払い',
            'postal_code' => '123-4567',
            'address' => '東京都テスト区1-1-1',
            'building' => 'テストビル101',
        ]);
    }

    public function test_purchased_item_is_displayed_as_sold_on_item_index()
    {
        $buyer = $this->createBuyer();

        $item = $this->createPurchasableItem(
            'Sold表示テスト商品'
        );

        $this
            ->actingAs($buyer)
            ->get(
                route('purchase.success', [
                    'item_id' => $item->id,
                    'payment_method' => 'カード支払い',
                ])
            );

        $response = $this
            ->actingAs($buyer)
            ->get(route('items.index'));

        $response->assertStatus(200);
        $response->assertSee($item->name);
        $response->assertSee('Sold');
    }

    public function test_purchased_item_is_added_to_mypage_buy_list()
    {
        $buyer = $this->createBuyer();

        $item = $this->createPurchasableItem(
            '購入一覧テスト商品'
        );

        $this
            ->actingAs($buyer)
            ->get(
                route('purchase.success', [
                    'item_id' => $item->id,
                    'payment_method' => 'カード支払い',
                ])
            );

        $response = $this
            ->actingAs($buyer)
            ->get(
                route('mypage.index', ['page' => 'buy'])
            );

        $response->assertStatus(200);
        $response->assertSee('購入した商品');
        $response->assertSee($item->name);

        $response->assertViewHas(
            'items',
            function ($items) use ($item) {
                return $items
                    ->pluck('id')
                    ->contains($item->id);
            }
        );
    }

    private function createBuyer(): User
    {
        return User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都テスト区1-1-1',
            'building' => 'テストビル101',
        ]);
    }

    private function createPurchasableItem(string $name): Item
    {
        $seller = User::factory()->create();

        $conditionId = DB::table('conditions')->insertGetId([
            'name' => '良好',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Item::create([
            'user_id' => $seller->id,
            'condition_id' => $conditionId,
            'name' => $name,
            'brand_name' => null,
            'price' => 1000,
            'description' => '商品購入機能テスト用の商品です。',
            'image_path' => 'https://example.com/purchase-item.jpg',
        ]);
    }
}