<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExhibitionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_exhibit_an_item()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $conditionId = DB::table('conditions')->insertGetId([
            'name' => '目立った傷や汚れなし',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $firstCategoryId = DB::table('categories')->insertGetId([
            'name' => 'ファッション',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $secondCategoryId = DB::table('categories')->insertGetId([
            'name' => 'レディース',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $image = UploadedFile::fake()->create(
            'exhibition-item.jpg',
            100,
            'image/jpeg'
        );

        $response = $this
            ->actingAs($user)
            ->post(route('items.store'), [
                'image' => $image,
                'category_ids' => [
                    $firstCategoryId,
                    $secondCategoryId,
                ],
                'condition_id' => $conditionId,
                'name' => '出品登録テスト商品',
                'brand_name' => 'テストブランド',
                'description' => '出品商品情報登録テスト用の商品です。',
                'price' => 5000,
            ]);

        $response->assertRedirect(
            route('mypage.index', ['page' => 'sell'])
        );

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'condition_id' => $conditionId,
            'name' => '出品登録テスト商品',
            'brand_name' => 'テストブランド',
            'description' => '出品商品情報登録テスト用の商品です。',
            'price' => 5000,
        ]);

        $item = Item::where(
            'name',
            '出品登録テスト商品'
        )->firstOrFail();

        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => $firstCategoryId,
        ]);

        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => $secondCategoryId,
        ]);

        $this->assertCount(
            2,
            DB::table('category_item')
                ->where('item_id', $item->id)
                ->get()
        );

        $this->assertNotNull($item->image_path);

        Storage::disk('public')->assertExists(
            $item->image_path
        );

        $mypageResponse = $this
            ->actingAs($user)
            ->get(
                route('mypage.index', ['page' => 'sell'])
            );

        $mypageResponse->assertStatus(200);
        $mypageResponse->assertSee($item->name);
    }
}