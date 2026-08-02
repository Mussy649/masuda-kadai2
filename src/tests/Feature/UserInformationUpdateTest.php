<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserInformationUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_registered_information_is_displayed_on_edit_page()
    {
        $user = User::factory()->create([
            'name' => '変更前ユーザー',
            'profile_image' => 'profile_images/before-profile.jpg',
            'postal_code' => '123-4567',
            'address' => '東京都テスト区1-1-1',
            'building' => '変更前ビル101',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('mypage.profile.edit'));

        $response->assertStatus(200);

        $response->assertSee(
            asset('storage/' . $user->profile_image),
            false
        );

        $response->assertSee(
            'value="変更前ユーザー"',
            false
        );

        $response->assertSee(
            'value="123-4567"',
            false
        );

        $response->assertSee(
            'value="東京都テスト区1-1-1"',
            false
        );

        $response->assertSee(
            'value="変更前ビル101"',
            false
        );
    }

    public function test_user_can_update_profile_information()
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'name' => '変更前ユーザー',
            'postal_code' => '123-4567',
            'address' => '東京都テスト区1-1-1',
            'building' => '変更前ビル101',
        ]);

        $profileImage = UploadedFile::fake()->create(
            'updated-profile.jpg',
            100,
            'image/jpeg'
        );

        $response = $this
            ->actingAs($user)
            ->patch(
                route('mypage.profile.update'),
                [
                    'profile_image' => $profileImage,
                    'name' => '変更後ユーザー',
                    'postal_code' => '987-6543',
                    'address' => '大阪府テスト市2-2-2',
                    'building' => '変更後マンション202',
                ]
            );

        $response->assertRedirect(route('mypage.index'));

        $response->assertSessionHas(
            'message',
            'プロフィールを更新しました。'
        );

        $user->refresh();

        $this->assertSame(
            '変更後ユーザー',
            $user->name
        );

        $this->assertSame(
            '987-6543',
            $user->postal_code
        );

        $this->assertSame(
            '大阪府テスト市2-2-2',
            $user->address
        );

        $this->assertSame(
            '変更後マンション202',
            $user->building
        );

        $this->assertNotNull($user->profile_image);

        Storage::disk('public')->assertExists(
            $user->profile_image
        );

        $mypageResponse = $this
            ->actingAs($user)
            ->get(route('mypage.index'));

        $mypageResponse->assertStatus(200);
        $mypageResponse->assertSee('変更後ユーザー');

        $mypageResponse->assertSee(
            asset('storage/' . $user->profile_image),
            false
        );
    }
}