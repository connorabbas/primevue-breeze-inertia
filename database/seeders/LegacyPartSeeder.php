<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Part;
use App\Models\Supplier;
use App\DTOs\ReplenishmentDataDTO;
use App\DTOs\IdentifierDTO;
use Illuminate\Support\Facades\Log;

class LegacyPartSeeder extends Seeder
{
    public function run()
    {
        $legacyParts = DB::connection('legacy')->table('part')->get();
        $totalCount = $legacyParts->count();
        $successCount = 0;
        $failureCount = 0;

        $this->command->info("Starting to seed {$totalCount} parts from legacy database.");

        foreach ($legacyParts as $legacyPart) {
            try {
                $supplier = Supplier::firstOrCreate(['id' => $legacyPart->supplier_id], ['name' => "Supplier {$legacyPart->supplier_id}"]);

                Part::create([
                    'id' => $legacyPart->part_id,
                    'part_number' => $legacyPart->part_nbr,
                    'quantity' => $legacyPart->part_qty,
                    'description' => $legacyPart->part_desc,
                    'identifiers' => $this->createIdentifiers($legacyPart),
                    'regulatory_information' => $this->createRegulatoryInformation($legacyPart),
                    'replenishment_data' => $this->createReplenishmentData($legacyPart),
                    'supplier_id' => $supplier->id,
                    'manufacturer_id' => null, // We'll set this later in the RelationshipSeeder
                ]);

                $successCount++;
            } catch (\Exception $e) {
                $failureCount++;
                $errorMessage = "Error seeding part {$legacyPart->part_id}: " . $e->getMessage();
                $this->command->error($errorMessage);
                Log::error($errorMessage);
            }
        }

        $this->command->info("Part seeding completed.");
        $this->command->info("Total parts: {$totalCount}");
        $this->command->info("Successfully seeded: {$successCount}");
        $this->command->error("Failed to seed: {$failureCount}");
    }

    private function createIdentifiers($legacyPart)
    {
        return IdentifierDTO::fromArray([
            ['type' => 'fda_listing_nbr', 'value' => $legacyPart->fda_listing_nbr],
            ['type' => 'hsc_code', 'value' => $legacyPart->hsc_code],
            ['type' => 'hsc_desc', 'value' => $legacyPart->hsc_desc]
        ]);
    }

    private function createRegulatoryInformation($legacyPart)
    {
        return IdentifierDTO::fromArray([
            [
                'type' => 'origin_country_code',
                'value' => $legacyPart->origin_country_code,
            ]
        ]);
    }

    private function createReplenishmentData($legacyPart)
    {
        return new ReplenishmentDataDTO(
            lead_days: $legacyPart->lead_days,
            purchaseTerms: [
                [
                    'minimum_quantity' => $legacyPart->min_order_qty1,
                    'cost_per_part' => $legacyPart->min_order_cost1,
                ],
                [
                    'minimum_quantity' => $legacyPart->min_order_qty2,
                    'cost_per_part' => $legacyPart->min_order_cost2,
                ],
                [
                    'minimum_quantity' => $legacyPart->min_order_qty3,
                    'cost_per_part' => $legacyPart->min_order_cost3,
                ],
            ]
        );
    }
}
