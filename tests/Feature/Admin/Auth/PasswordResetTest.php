<?php

namespace Tests\Feature\Admin\Auth;

use App\Models\Admin;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get(route('admin.password.request'));

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        $admin = Admin::factory()->create();

        $this->post(route('admin.password.email'), ['email' => $admin->email]);

        Notification::assertSentTo($admin, ResetPassword::class);
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        Notification::fake();

        $admin = Admin::factory()->create();

        $this->post(route('admin.password.email'), ['email' => $admin->email]);

        Notification::assertSentTo($admin, ResetPassword::class, function ($notification) {
            $response = $this->get(route('admin.password.reset', ['token' => $notification->token]));

            $response->assertStatus(200);

            return true;
        });
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        $admin = Admin::factory()->create();

        $this->post(route('admin.password.email'), ['email' => $admin->email]);

        Notification::assertSentTo($admin, ResetPassword::class, function ($notification) use ($admin) {
            $response = $this->post(route('admin.password.store'), [
                'token' => $notification->token,
                'email' => $admin->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response
                ->assertSessionHasNoErrors()
                ->assertRedirect(route('admin.login'));

            return true;
        });
    }
}
