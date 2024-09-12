<?php

namespace Tests\Feature\Admin\Auth;

use Tests\TestCase;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_screen_can_be_rendered(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
    }

    public function test_admins_can_authenticate_using_the_login_screen(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated('admin');
        $response->assertRedirect(route('admin.dashboard', absolute: false));
    }

    public function test_admins_can_not_authenticate_with_invalid_password(): void
    {
        $admin = Admin::factory()->create();

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_admins_can_logout(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.logout'));

        $this->assertGuest();
        $response->assertRedirect(route('welcome'));
    }
}
