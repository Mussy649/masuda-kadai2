<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchase_page_displays_payment_method_options()
    {
        $buyer = $this->createBuyer();
        $item = $this->createPurchasableItem();

        $response = $this
            ->actingAs($buyer)
            ->get(
                route('purchase.show', [
                    'item_id' => $item->id,
                ])
            );

        $response->assertStatus(200);
        $response->assertSee(
            'name="payment_method"',
            false
        );
        $response->assertSee(
            'value="コンビニ払い"',
            false
        );
        $response->assertSee(
            'value="カード支払い"',
            false
        );
        $response->assertSee(
            'id="selected-payment"',
            false
        );
        $response->assertSee('未選択');
    }

    public function test_selected_payment_method_is_reflected()
    {
        $buyer = $this->createBuyer();
        $item = $this->createPurchasableItem();

        $response = $this
            ->withSession([
                '_old_input' => [
                    'payment_method' => 'カード支払い',
                ],
            ])
            ->actingAs($buyer)
            ->get(
                route('purchase.show', [
                    'item_id' => $item->id,
                ])
            );

        $response->assertStatus(200);

        $response->assertSeeInOrder([
            'value="カード支払い"',
            'selected',
            'カード支払い',
        ], false);

        $response->assertSeeInOrder([
            'id="selected-payment"',
            'カード支払い',
        ], false);

        $response->assertSee(
            "paymentSelect.addEventListener('change'",
            false
        );

        $response->assertSee(
            "selectedPayment.textContent = this.value || '未選択';",
            false
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

    private function createPurchasableItem(): Item
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
            'name' => '支払い方法テスト商品',
            'brand_name' => null,
            'price' => 1000,
            'description' => '支払い方法選択テスト用の商品です。',
            'image_path' => 'https://example.com/payment-item.jpg',
        ]);
    }
}