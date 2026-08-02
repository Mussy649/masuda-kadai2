<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_updated_address_is_reflected_on_purchase_page()
    {
        $buyer = $this->createBuyer();

        $item = $this->createPurchasableItem(
            '配送先反映テスト商品'
        );

        $response = $this
            ->actingAs($buyer)
            ->put(
                route('purchase.address.update', [
                    'item_id' => $item->id,
                ]),
                [
                    'postal_code' => '987-6543',
                    'address' => '大阪府テスト市2-2-2',
                    'building' => '変更後マンション202',
                ]
            );

        $response->assertRedirect(
            route('purchase.show', [
                'item_id' => $item->id,
            ])
        );

        $this->assertDatabaseHas('users', [
            'id' => $buyer->id,
            'postal_code' => '987-6543',
            'address' => '大阪府テスト市2-2-2',
            'building' => '変更後マンション202',
        ]);

        $purchasePageResponse = $this
            ->actingAs($buyer->fresh())
            ->get(
                route('purchase.show', [
                    'item_id' => $item->id,
                ])
            );

        $purchasePageResponse->assertStatus(200);
        $purchasePageResponse->assertSee('987-6543');
        $purchasePageResponse->assertSee(
            '大阪府テスト市2-2-2'
        );
        $purchasePageResponse->assertSee(
            '変更後マンション202'
        );
    }

    public function test_updated_address_is_saved_with_purchase()
    {
        $buyer = $this->createBuyer();

        $item = $this->createPurchasableItem(
            '購入住所保存テスト商品'
        );

        $this
            ->actingAs($buyer)
            ->put(
                route('purchase.address.update', [
                    'item_id' => $item->id,
                ]),
                [
                    'postal_code' => '987-6543',
                    'address' => '大阪府テスト市2-2-2',
                    'building' => '変更後マンション202',
                ]
            );

        $purchaseResponse = $this
            ->actingAs($buyer->fresh())
            ->get(
                route('purchase.success', [
                    'item_id' => $item->id,
                    'payment_method' => 'カード支払い',
                ])
            );

        $purchaseResponse->assertRedirect(
            route('mypage.index', [
                'page' => 'buy',
            ])
        );

        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'カード支払い',
            'postal_code' => '987-6543',
            'address' => '大阪府テスト市2-2-2',
            'building' => '変更後マンション202',
        ]);
    }

    private function createBuyer(): User
    {
        return User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都テスト区1-1-1',
            'building' => '変更前ビル101',
        ]);
    }

    private function createPurchasableItem(
        string $name
    ): Item {
        $seller = User::factory()->create();

        $conditionId = DB::table('conditions')
            ->insertGetId([
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
            'description' => '配送先変更テスト用の商品です。',
            'image_path' => 'https://example.com/address-item.jpg',
        ]);
    }
}