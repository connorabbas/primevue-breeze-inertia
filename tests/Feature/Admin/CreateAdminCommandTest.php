<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Admin;
use App\Notifications\AdminVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateAdminCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the command with valid input.
     */
    public function test_command_creates_admin_with_valid_input()
    {
        $this->artisan('admin:create')
            ->expectsQuestion('Enter the full name of the admin user', 'John Doe')
            ->expectsQuestion('Enter the email of the admin user', 'john@example.com')
            ->expectsQuestion('Enter the password', 'password123')
            ->expectsQuestion('Confirm the password', 'password123')
            ->expectsOutput('Admin successfully created!')
            ->assertExitCode(0);

        $this->assertDatabaseHas('admins', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $admin = Admin::where('email', 'john@example.com')->first();
        $this->assertTrue(Hash::check('password123', $admin->password));
    }

    /**
     * Test the command with invalid email input.
     */
    public function test_command_fails_with_invalid_email()
    {
        $this->artisan('admin:create')
            ->expectsQuestion('Enter the full name of the admin user', 'John Doe')
            ->expectsQuestion('Enter the email of the admin user', 'not-an-email')
            ->expectsQuestion('Enter the password', 'password123')
            ->expectsQuestion('Confirm the password', 'password123')
            ->expectsOutput('The email field must be a valid email address.')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('admins', [
            'name' => 'John Doe',
            'email' => 'not-an-email',
        ]);
    }

    /**
     * Test the command with a password confirmation mismatch.
     */
    public function test_command_fails_with_password_confirmation_mismatch()
    {
        $this->artisan('admin:create')
            ->expectsQuestion('Enter the full name of the admin user', 'John Doe')
            ->expectsQuestion('Enter the email of the admin user', 'john@example.com')
            ->expectsQuestion('Enter the password', 'password123')
            ->expectsQuestion('Confirm the password', 'differentpassword')
            ->expectsOutput('The password field confirmation does not match.')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('admins', [
            'email' => 'john@example.com',
        ]);
    }

    /**
     * Test the command fails when required fields are missing.
     */
    public function test_command_fails_when_required_fields_are_missing()
    {
        $this->artisan('admin:create')
            ->expectsQuestion('Enter the full name of the admin user', '')
            ->expectsQuestion('Enter the email of the admin user', '')
            ->expectsQuestion('Enter the password', '')
            ->expectsQuestion('Confirm the password', '')
            ->expectsOutput('The name field is required.')
            ->expectsOutput('The email field is required.')
            ->expectsOutput('The password field is required.')
            ->assertExitCode(0);
    }

    /**
     * Test the command sends a verification email if the admin implements MustVerifyEmail.
     */
    public function test_command_sends_verification_email_if_admin_must_verify()
    {
        if (!(new Admin() instanceof MustVerifyEmail)) {
            $this->markTestSkipped();
        }
        Notification::fake();

        $this->artisan('admin:create')
            ->expectsQuestion('Enter the full name of the admin user', 'Jane Doe')
            ->expectsQuestion('Enter the email of the admin user', 'jane@example.com')
            ->expectsQuestion('Enter the password', 'password123')
            ->expectsQuestion('Confirm the password', 'password123')
            ->expectsOutput('Admin successfully created!')
            ->assertExitCode(0);

        $admin = Admin::where('email', 'jane@example.com')->first();
        Notification::assertSentTo($admin, AdminVerifyEmail::class);
    }
}
