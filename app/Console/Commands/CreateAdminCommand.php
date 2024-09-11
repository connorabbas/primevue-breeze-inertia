<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return;
        }

        // Incase of using admins with roles, using spatie/laravel-permission
        //$roles = Role::pluck('name');
        //$chosenRoles = $this->choice('Which roles should this admin have?', $roles->toArray(), null, null, true);

        $user = Admin::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
        //$user->assignRole($chosenRoles);

        $this->info('Admin successfully created!');
    }
}
