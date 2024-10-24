<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Location;
use App\Models\Supplier;
use Illuminate\Support\Facades\Log;

class LegacyLocationSeeder extends Seeder
{
    public function run()
    {
        $legacyLocations = DB::connection('legacy')->table('location')->get();
        $totalCount = $legacyLocations->count();
        $successCount = 0;
        $failureCount = 0;

        $this->command->info("Starting to seed {$totalCount} locations from legacy database.");

        foreach ($legacyLocations as $legacyLocation) {
            try {
                $locationType = $this->getLocationType($legacyLocation);

                $supplier_id = null;
                if ($legacyLocation->supplier_id) {
                    $supplier = Supplier::firstOrCreate(['id' => $legacyLocation->supplier_id], ['name' => $legacyLocation->location_name]);
                    $supplier_id = $supplier->id;
                }

                $parent_id = null;
                if ($locationType['type'] === Location::TYPE_VIRTUAL) {
                    $parent_id = Location::where('type', Location::TYPE_WAREHOUSE)->first()->id ?? null;
                }

                Location::create([
                    'id' => $legacyLocation->location_id,
                    'name' => $legacyLocation->location_name,
                    'type' => $locationType['type'],
                    'virtual_type' => $locationType['virtual_type'] ?? null,
                    'supplier_id' => $supplier_id,
                    'parent_id' => $parent_id,
                ]);

                $successCount++;
                $this->command->info("Successfully seeded location {$legacyLocation->location_id}");
            } catch (\Exception $e) {
                $failureCount++;
                $errorMessage = "Error seeding location {$legacyLocation->location_id}: " . $e->getMessage();
                $this->command->error($errorMessage);
                Log::error($errorMessage);
            }
        }

        $this->command->info("Location seeding completed.");
        $this->command->info("Total locations: {$totalCount}");
        $this->command->info("Successfully seeded: {$successCount}");
        $this->command->error("Failed to seed: {$failureCount}");
    }

    private function getLocationType($legacyLocation)
    {
        if ($legacyLocation->buyer_ind) {
            return ['type' => Location::TYPE_WAREHOUSE];
        } elseif ($legacyLocation->supplier_id) {
            return ['type' => Location::TYPE_SUPPLIER];
        } elseif ($legacyLocation->bill_to_ind) {
            return [
                'type' => Location::TYPE_VIRTUAL,
                'virtual_type' => Location::VIRTUAL_TYPE_BILL_TO
            ];
        } elseif ($legacyLocation->ship_to_ind) {
            return [
                'type' => Location::TYPE_VIRTUAL,
                'virtual_type' => Location::VIRTUAL_TYPE_SHIP_TO
            ];
        } else {
            return ['type' => Location::TYPE_VIRTUAL];
        }
    }
}
