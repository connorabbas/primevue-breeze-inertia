<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Part;
use App\Models\Location;
use App\Models\Manufacturer;

class BusinessSeeder extends Seeder
{
    public function run(): void
    {
        // Create suppliers
        $suppliers = Supplier::factory()->count(5)->create();

        // Create manufacturers
        $manufacturers = Manufacturer::factory()->count(3)->create();

        // Create parts for each supplier
        foreach ($suppliers as $supplier) {
            Part::factory()
                ->count(10)
                ->state([
                    'supplier_id' => $supplier->id,
                    'manufacturer_id' => $manufacturers->random()->id,
                ])
                ->create();
        }

        // Create locations
        Location::factory()->warehouse()->count(2)->create();
        Location::factory()->supplier()->count(3)->create();
        Location::factory()->rack()->count(5)->create();
        Location::factory()->bin()->count(10)->create();

        // Create virtual locations (bill-to and ship-to)
        Location::factory()->billTo()->count(3)->create();
        Location::factory()->shipTo()->count(3)->create();
    }
}
