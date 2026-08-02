<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserInformationTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_and_selling_items_are_displayed()
    {
        $user = User::factory()->create([
            'name' => 'マイページテストユーザー',
            'profile_image' => 'profile_images/test-profile.jpg',
        ]);

        $conditionId = $this->createCondition();

        $item = $this->createItem(
            $user,
            $conditionId,
            '出品一覧テスト商品'
        );

        $response = $this
            ->actingAs($user)
            ->get(
                route('mypage.index', [
                    'page' => 'sell',
                ])
            );

        $response->assertStatus(200);
        $response->assertSee('マイページテストユーザー');
        $response->assertSee(
            asset('storage/' . $user->profile_image),
            false
        );
        $response->assertSee('出品した商品');
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

    public function test_purchased_items_are_displayed()
    {
        $buyer = User::factory()->create([
            'name' => '購入者テストユーザー',
        ]);

        $seller = User::factory()->create();

        $conditionId = $this->createCondition();

        $item = $this->createItem(
            $seller,
            $conditionId,
            '購入一覧テスト商品'
        );

        DB::table('purchases')->insert([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'カード支払い',
            'postal_code' => '123-4567',
            'address' => '東京都テスト区1-1-1',
            'building' => 'テストビル101',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this
            ->actingAs($buyer)
            ->get(
                route('mypage.index', [
                    'page' => 'buy',
                ])
            );

        $response->assertStatus(200);
        $response->assertSee('購入者テストユーザー');
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
            'description' => 'ユーザー情報取得テスト用の商品です。',
            'image_path' => 'https://example.com/mypage-item.jpg',
        ]);
    }
}