<?php

namespace Tests\Feature\Admin\Auth;

use App\Models\Admin;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered(): void
    {
        $admin = Admin::factory()->unverified()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.verification.notice'));

        $response->assertStatus(200);
    }

    public function test_email_can_be_verified(): void
    {
        $admin = Admin::factory()->unverified()->create();

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'admin.verification.verify',
            now()->addMinutes(60),
            ['id' => $admin->id, 'hash' => sha1($admin->email)]
        );

        $response = $this->actingAs($admin, 'admin')->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($admin->fresh()->hasVerifiedEmail());
        $response->assertRedirect(route('admin.dashboard', absolute: false) . '?verified=1');
    }

    public function test_email_is_not_verified_with_invalid_hash(): void
    {
        $admin = Admin::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'admin.verification.verify',
            now()->addMinutes(60),
            ['id' => $admin->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($admin, 'admin')->get($verificationUrl);

        $this->assertFalse($admin->fresh()->hasVerifiedEmail());
    }
}
