<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_open_home_page(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Logowanie');
    }

    public function test_user_can_register_and_login(): void
    {
        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('login'));

        $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticated();
    }

    public function test_protected_routes_require_login(): void
    {
        $this->get(route('listings.create'))
            ->assertRedirect(route('login'));
    }

    public function test_admin_routes_require_admin_user(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'is_banned' => false,
        ]);

        $this->actingAs($user)
            ->get(route('admin.services.index'))
            ->assertForbidden();
    }
}
