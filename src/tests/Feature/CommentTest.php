<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_submit_comment()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $conditionId = $this->createCondition();

        $item = $this->createItem(
            $seller,
            $conditionId,
            'コメント送信テスト商品'
        );

        $response = $this
            ->actingAs($user)
            ->from(route('items.show', ['item_id' => $item->id]))
            ->post(
                route('comments.store', ['item_id' => $item->id]),
                [
                    'comment' => 'コメント送信テストです。',
                ]
            );

        $response->assertRedirect(
            route('items.show', ['item_id' => $item->id])
        );

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'コメント送信テストです。',
        ]);

        $detailResponse = $this
            ->actingAs($user)
            ->get(route('items.show', ['item_id' => $item->id]));

        $detailResponse->assertViewHas(
            'item',
            function ($viewItem) {
                return $viewItem->comments_count === 1;
            }
        );

        $detailResponse->assertSee('コメント送信テストです。');
    }

    public function test_guest_cannot_submit_comment()
    {
        $seller = User::factory()->create();
        $conditionId = $this->createCondition();

        $item = $this->createItem(
            $seller,
            $conditionId,
            '未ログインコメントテスト商品'
        );

        $response = $this->post(
            route('comments.store', ['item_id' => $item->id]),
            [
                'comment' => '未ログインユーザーのコメントです。',
            ]
        );

        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'comment' => '未ログインユーザーのコメントです。',
        ]);

        $this->assertGuest();
    }

    public function test_comment_is_required()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $conditionId = $this->createCondition();

        $item = $this->createItem(
            $seller,
            $conditionId,
            'コメント未入力テスト商品'
        );

        $response = $this
            ->actingAs($user)
            ->from(route('items.show', ['item_id' => $item->id]))
            ->post(
                route('comments.store', ['item_id' => $item->id]),
                [
                    'comment' => '',
                ]
            );

        $response->assertRedirect(
            route('items.show', ['item_id' => $item->id])
        );

        $response->assertSessionHasErrors([
            'comment' => 'コメントを入力してください。',
        ]);

        $this->assertDatabaseMissing('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_comment_must_not_exceed_255_characters()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $conditionId = $this->createCondition();

        $item = $this->createItem(
            $seller,
            $conditionId,
            'コメント文字数テスト商品'
        );

        $response = $this
            ->actingAs($user)
            ->from(route('items.show', ['item_id' => $item->id]))
            ->post(
                route('comments.store', ['item_id' => $item->id]),
                [
                    'comment' => str_repeat('あ', 256),
                ]
            );

        $response->assertRedirect(
            route('items.show', ['item_id' => $item->id])
        );

        $response->assertSessionHasErrors([
            'comment' => 'コメントは255文字以内で入力してください。',
        ]);

        $this->assertDatabaseMissing('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
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
            'description' => 'コメント機能テスト用の商品です。',
            'image_path' => 'https://example.com/comment-item.jpg',
        ]);
    }
}