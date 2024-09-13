<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class CreateAdminCommand extends Command
{
    protected $signature = 'admin:create';
    protected $description = 'Create a new admin user with roles';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->ask('Enter the full name of the admin user');
        $email = $this->ask('Enter the email of the admin user');
        $password = $this->secret('Enter the password');
        $confirmPassword = $this->secret('Confirm the password');

        // Validation logic
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $confirmPassword,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return;
        }

        $admin = Admin::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
        if ($admin instanceof MustVerifyEmail) {
            $admin->sendEmailVerificationNotification();
        }

        $this->info('Admin successfully created!');
    }
}
