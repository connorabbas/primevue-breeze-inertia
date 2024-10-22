<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run()
    {
        User::factory()->create([
            'name' => 'Greg Patton',
            'email' => 'gregory@patton.dev',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'Todd Doner',
            'email' => 'todd@donerindustries.com',
            'password' => Hash::make('password'),
        ]);
    }
}
