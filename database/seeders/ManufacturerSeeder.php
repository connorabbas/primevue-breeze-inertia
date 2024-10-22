<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Manufacturer;
use Illuminate\Support\Facades\Log;

class ManufacturerSeeder extends Seeder
{
    public function run()
    {
        $legacyManufacturers = DB::connection('legacy')->table('manufacturer')->get();

        foreach ($legacyManufacturers as $legacyManufacturer) {
            try {
                Manufacturer::create([
                    'id' => $legacyManufacturer->man_id,
                    'name' => $legacyManufacturer->man_name,
                    // Add any other fields that are in your Manufacturer model
                ]);
            } catch (\Exception $e) {
                Log::error("Error seeding manufacturer {$legacyManufacturer->man_id}: " . $e->getMessage());
            }
        }
    }
}
