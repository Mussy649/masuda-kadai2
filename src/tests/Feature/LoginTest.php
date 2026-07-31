<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_is_required()
    {
        $response = $this
            ->from('/login')
            ->post('/login', [
                'email' => '',
                'password' => 'password',
            ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください。',
        ]);

        $this->assertGuest();
    }

    public function test_password_is_required()
    {
        $response = $this
            ->from('/login')
            ->post('/login', [
                'email' => 'login-test@example.com',
                'password' => '',
            ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください。',
        ]);

        $this->assertGuest();
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'login-test@example.com',
        ]);

        $response = $this
            ->from('/login')
            ->post('/login', [
                'email' => 'login-test@example.com',
                'password' => 'wrong-password',
            ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスまたはパスワードが正しくありません。',
        ]);

        $this->assertGuest();
    }

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'login-test@example.com',
        ]);

        $response = $this->post('/login', [
            'email' => 'login-test@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/home');

        $this->assertAuthenticatedAs($user);
    }
}