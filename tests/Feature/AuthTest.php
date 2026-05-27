<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_email_and_password(): void
    {
        $response = $this->post('/register', [
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/portal');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'demo@example.com']);
    }

    public function test_user_can_login_and_view_portal(): void
    {
        $user = User::factory()->create([
            'email' => 'demo@example.com',
            'password' => 'password',
        ]);

        $this->post('/login', [
            'email' => 'demo@example.com',
            'password' => 'password',
        ])->assertRedirect('/portal');

        $this->assertAuthenticatedAs($user);
        $this->get('/portal')->assertOk()->assertSee('Welcome, '.$user->name);
    }

    public function test_guest_is_redirected_from_portal_to_login(): void
    {
        $this->get('/portal')->assertRedirect('/login');
    }

    public function test_unverified_user_can_view_portal_while_email_verification_is_disabled(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->get('/portal')
            ->assertOk()
            ->assertSee('Welcome, '.$user->name);
    }

    public function test_user_can_request_password_reset_link(): void
    {
        Notification::fake();

        $user = User::factory()->create(['email' => 'demo@example.com']);

        $this->post('/forgot-password', ['email' => 'demo@example.com'])
            ->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_user_can_reset_password(): void
    {
        $user = User::factory()->create(['email' => 'demo@example.com']);
        $token = Password::createToken($user);

        $this->post('/reset-password', [
            'token' => $token,
            'email' => 'demo@example.com',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertRedirect('/login');

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }
}
