<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_verification_email_is_sent_after_registration()
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'メール認証テスト',
            'email' => 'verification@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/home');

        $user = User::where(
            'email',
            'verification@example.com'
        )->firstOrFail();

        $this->assertFalse($user->hasVerifiedEmail());

        Notification::assertSentTo(
            $user,
            VerifyEmail::class
        );
    }

    public function test_unverified_user_can_view_verification_notice()
    {
        $user = User::factory()
            ->unverified()
            ->create();

        $response = $this
            ->actingAs($user)
            ->get(route('verification.notice'));

        $response->assertStatus(200);

        $response->assertSee(
            '登録していただいたメールアドレス宛に'
        );

        $response->assertSee(
            '認証メールを送付しました。'
        );

        $response->assertSee(
            'メール認証を完了してください。'
        );

        $response->assertSee('認証はこちらから');

        $response->assertSee(
            'http://localhost:8025',
            false
        );

        $response->assertSee(
            route('verification.send'),
            false
        );
    }

    public function test_user_can_verify_email_and_proceed_to_profile_edit()
    {
        Event::fake();

        $user = User::factory()
            ->unverified()
            ->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1(
                    $user->getEmailForVerification()
                ),
            ]
        );

        $response = $this
            ->actingAs($user)
            ->get($verificationUrl);

        $response->assertRedirect('/home?verified=1');

        $this->assertTrue(
            $user->fresh()->hasVerifiedEmail()
        );

        Event::assertDispatched(Verified::class);

        $homeResponse = $this
            ->actingAs($user->fresh())
            ->get('/home');

        $homeResponse->assertRedirect(
            route('mypage.profile.edit')
        );
    }
}