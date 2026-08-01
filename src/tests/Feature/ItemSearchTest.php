<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_items_can_be_searched_by_partial_name()
    {
        $seller = User::factory()->create();
        $conditionId = $this->createCondition();

        $matchingItem = $this->createItem(
            $seller,
            $conditionId,
            '高性能ノートPC'
        );

        $nonMatchingItem = $this->createItem(
            $seller,
            $conditionId,
            'ショルダーバッグ'
        );

        $response = $this->get('/?keyword=ノート');

        $response->assertStatus(200);
        $response->assertSee($matchingItem->name);
        $response->assertDontSee($nonMatchingItem->name);
    }

    public function test_search_keyword_is_preserved_in_mylist()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $conditionId = $this->createCondition();

        $matchingItem = $this->createItem(
            $seller,
            $conditionId,
            'マイリスト用ノートPC'
        );

        $nonMatchingItem = $this->createItem(
            $seller,
            $conditionId,
            'マイリスト用腕時計'
        );

        Like::create([
            'user_id' => $user->id,
            'item_id' => $matchingItem->id,
        ]);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $nonMatchingItem->id,
        ]);

        $searchResponse = $this
            ->actingAs($user)
            ->get('/?keyword=ノート');

        $searchResponse->assertStatus(200);
        $searchResponse->assertSee(
            route('items.index', [
                'tab' => 'mylist',
                'keyword' => 'ノート',
            ])
        );

        $mylistResponse = $this
            ->actingAs($user)
            ->get('/?tab=mylist&keyword=ノート');

        $mylistResponse->assertStatus(200);
        $mylistResponse->assertSee($matchingItem->name);
        $mylistResponse->assertDontSee($nonMatchingItem->name);
        $mylistResponse->assertSee(
            'value="ノート"',
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
            'description' => '商品検索テスト用の商品です。',
            'image_path' => 'https://example.com/search-item.jpg',
        ]);
    }
}