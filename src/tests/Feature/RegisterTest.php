<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_name_is_required()
    {
        $response = $this
            ->from('/register')
            ->post('/register', $this->validRegistrationData([
                'name' => '',
            ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors([
            'name' => 'ユーザー名を入力してください。',
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => 'register-test@example.com',
        ]);
    }

    public function test_email_is_required()
    {
        $response = $this
            ->from('/register')
            ->post('/register', $this->validRegistrationData([
                'email' => '',
            ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください。',
        ]);
    }

    public function test_password_is_required()
    {
        $response = $this
            ->from('/register')
            ->post('/register', $this->validRegistrationData([
                'password' => '',
                'password_confirmation' => '',
            ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください。',
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => 'register-test@example.com',
        ]);
    }

    public function test_password_must_be_at_least_eight_characters()
    {
        $response = $this
            ->from('/register')
            ->post('/register', $this->validRegistrationData([
                'password' => '1234567',
                'password_confirmation' => '1234567',
            ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors([
            'password' => 'パスワードは8文字以上で入力してください。',
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => 'register-test@example.com',
        ]);
    }

    public function test_password_confirmation_must_match()
    {
        $response = $this
            ->from('/register')
            ->post('/register', $this->validRegistrationData([
                'password' => 'password',
                'password_confirmation' => 'different-password',
            ]));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors([
            'password' => 'パスワードと確認用パスワードが一致しません。',
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => 'register-test@example.com',
        ]);
    }

    public function test_user_can_register_with_valid_information()
    {
        $registrationData = $this->validRegistrationData();

        $response = $this->post('/register', $registrationData);

        $response->assertRedirect('/home');

        $this->assertDatabaseHas('users', [
            'name' => '登録テストユーザー',
            'email' => 'register-test@example.com',
        ]);

        $user = User::where(
            'email',
            'register-test@example.com'
        )->firstOrFail();

        $this->assertAuthenticatedAs($user);

        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();

        $this->get('/home')
        ->assertRedirect(route('verification.notice'));

        $user->markEmailAsVerified();

        $this->actingAs($user->fresh());

        $this->get('/home')
        ->assertRedirect(route('mypage.profile.edit'));
    }

    private function validRegistrationData(
        array $overrides = []
    ): array {
        return array_merge([
            'name' => '登録テストユーザー',
            'email' => 'register-test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ], $overrides);
    }
}