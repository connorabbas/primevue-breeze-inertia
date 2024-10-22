<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Starting legacy data import process...');

        $this->call([
            TestUserSeeder::class,
            LegacySupplierSeeder::class,
            LegacyLocationSeeder::class,
            LegacyPartSeeder::class,
            LegacyProductSeeder::class,
            LegacyGtinSeeder::class,
            RelationshipSeeder::class,
        ]);

        $this->command->info('Legacy data import process completed.');
    }
}
