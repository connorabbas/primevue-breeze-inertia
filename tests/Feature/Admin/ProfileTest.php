<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $admin = Admin::factory()->create();

        $response = $this
            ->actingAs($admin, 'admin')
            ->get(route('admin.profile.edit'));

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $admin = Admin::factory()->create();

        $response = $this
            ->actingAs($admin, 'admin')
            ->patch(route('admin.profile.update'), [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.profile.edit'));

        $admin->refresh();

        $this->assertSame('Test User', $admin->name);
        $this->assertSame('test@example.com', $admin->email);
        $this->assertNull($admin->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $admin = Admin::factory()->create();

        $response = $this
            ->actingAs($admin, 'admin')
            ->patch(route('admin.profile.update'), [
                'name' => 'Test User',
                'email' => $admin->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.profile.edit'));

        $this->assertNotNull($admin->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $admin = Admin::factory()->create();

        $response = $this
            ->actingAs($admin, 'admin')
            ->delete(route('admin.profile.destroy'), [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($admin->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $admin = Admin::factory()->create();

        $response = $this
            ->actingAs($admin, 'admin')
            ->from(route('admin.profile.edit'))
            ->delete(route('admin.profile.destroy'), [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrors('password')
            ->assertRedirect(route('admin.profile.edit'));

        $this->assertNotNull($admin->fresh());
    }
}
