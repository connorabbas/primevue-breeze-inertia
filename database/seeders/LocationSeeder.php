<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Bentonville Warehouse
        $warehouse = Location::create([
            'name' => 'Bentonville Warehouse',
            'type' => Location::TYPE_WAREHOUSE,
            'address' => [
                'street' => '10636 W HWY 72 STE 601',
                'city' => 'BENTONVILLE',
                'state' => 'AR',
                'zip' => '72712',
                'phone' => '4798029953',
            ],
        ]);
        $production = Location::create([
            'name' => 'Production',
            'type' => Location::TYPE_VIRTUAL,
            'parent_id' => $warehouse->id,
            'address' => [
                'street' => '10636 W HWY 72 STE 601',
                'city' => 'BENTONVILLE',
                'state' => 'AR',
                'zip' => '72712',
                'phone' => '4798029953',
            ],
        ]);

        // Create Airlife Virtual Location
        Location::create([
            'name' => 'Airlife',
            'type' => Location::TYPE_VIRTUAL,
            'parent_id' => $warehouse->id,
            'address' => [
                'description' => 'Westmed in El Paso',
            ],
        ]);

        // Create Amazon FBA Virtual Location
        Location::create([
            'name' => 'Amazon FBA',
            'type' => Location::TYPE_VIRTUAL,
            'parent_id' => $warehouse->id,
        ]);
    }
}
