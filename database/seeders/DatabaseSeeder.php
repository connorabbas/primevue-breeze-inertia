<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Starting legacy data import process...');
        try {
            if (DB::connection('legacy')) {
                $this->call([
                    TestUserSeeder::class,
                    BuildDefaultSupplierSeeder::class, // Add default supplier
                    LegacySupplierSeeder::class,
                    LegacyLocationSeeder::class,
                    LegacyPartSeeder::class,
                    LegacyProductSeeder::class,
                    LegacyGtinSeeder::class,
                    RelationshipSeeder::class,
                ]);

                $this->command->info('Legacy data import process completed.');
            }
        } catch (Exception $e) {
            $this->command->error('Exception: ' . $e->getMessage());
            Log::warning('Exception occurred: ', [$e]);
        }
    }
}
