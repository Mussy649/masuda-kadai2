<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_like_an_item_and_like_count_increases()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $conditionId = $this->createCondition();

        $item = $this->createItem(
            $seller,
            $conditionId,
            'いいね登録テスト商品'
        );

        $response = $this
            ->actingAs($user)
            ->from(route('items.show', ['item_id' => $item->id]))
            ->post(route('likes.store', ['item_id' => $item->id]));

        $response->assertRedirect(
            route('items.show', ['item_id' => $item->id])
        );

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $detailResponse = $this
            ->actingAs($user)
            ->get(route('items.show', ['item_id' => $item->id]));

        $detailResponse->assertViewHas(
            'item',
            function ($viewItem) {
                return $viewItem->likes_count === 1;
            }
        );
    }

    public function test_liked_icon_changes_appearance()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $conditionId = $this->createCondition();

        $item = $this->createItem(
            $seller,
            $conditionId,
            'いいねアイコンテスト商品'
        );

        DB::table('likes')->insert([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('items.show', ['item_id' => $item->id]));

        $response->assertStatus(200);
        $response->assertSee(
            'item-action__button--liked',
            false
        );
        $response->assertSee('aria-label="いいねを解除する"', false);
    }

    public function test_user_can_remove_like_and_like_count_decreases()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $conditionId = $this->createCondition();

        $item = $this->createItem(
            $seller,
            $conditionId,
            'いいね解除テスト商品'
        );

        DB::table('likes')->insert([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this
            ->actingAs($user)
            ->from(route('items.show', ['item_id' => $item->id]))
            ->delete(route('likes.destroy', ['item_id' => $item->id]));

        $response->assertRedirect(
            route('items.show', ['item_id' => $item->id])
        );

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $detailResponse = $this
            ->actingAs($user)
            ->get(route('items.show', ['item_id' => $item->id]));

        $detailResponse->assertViewHas(
            'item',
            function ($viewItem) {
                return $viewItem->likes_count === 0;
            }
        );

        $detailResponse->assertDontSee(
            'item-action__button--liked',
            false
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
        User $seller,
        int $conditionId,
        string $name
    ): Item {
        return Item::create([
            'user_id' => $seller->id,
            'condition_id' => $conditionId,
            'name' => $name,
            'brand_name' => null,
            'price' => 1000,
            'description' => 'いいね機能テスト用の商品です。',
            'image_path' => 'https://example.com/like-item.jpg',
        ]);
    }
}