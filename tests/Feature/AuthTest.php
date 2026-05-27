<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
