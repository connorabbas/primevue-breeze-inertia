<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Part;
use App\DTOs\IdentifierDTO;
use App\DTOs\ReplenishmentDataDTO;
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
                // Create replenishment data
                $purchaseTerms = [];
                if ($legacyPart->min_order_qty1 > 0) {
                    $purchaseTerms[] = [
                        'minimum_quantity' => $legacyPart->min_order_qty1,
                        'cost_per_part' => $legacyPart->min_order_cost1
                    ];
                }
                if ($legacyPart->min_order_qty2 > 0) {
                    $purchaseTerms[] = [
                        'minimum_quantity' => $legacyPart->min_order_qty2,
                        'cost_per_part' => $legacyPart->min_order_cost2
                    ];
                }
                if ($legacyPart->min_order_qty3 > 0) {
                    $purchaseTerms[] = [
                        'minimum_quantity' => $legacyPart->min_order_qty3,
                        'cost_per_part' => $legacyPart->min_order_cost3
                    ];
                }

                $replenishmentData = new ReplenishmentDataDTO(
                    lead_days: $legacyPart->lead_days,
                    purchaseTerms: $purchaseTerms
                );

                // Create identifiers
                $identifiers = IdentifierDTO::fromArray([
                    [
                        'type' => 'fda_listing_nbr',
                        'value' => $legacyPart->fda_listing_nbr
                    ],
                    [
                        'type' => 'hsc_code',
                        'value' => $legacyPart->hsc_code
                    ],
                    [
                        'type' => 'hsc_desc',
                        'value' => $legacyPart->hsc_desc
                    ]
                ]);

                Part::updateOrCreate(
                    ['id' => $legacyPart->part_id],
                    [
                        'part_number' => $legacyPart->part_nbr,
                        'quantity' => $legacyPart->part_qty,
                        'description' => $legacyPart->part_desc,
                        'identifiers' => $identifiers,
                        'replenishment_data' => $replenishmentData,
                        'supplier_id' => $legacyPart->supplier_id,
                        'manufacturer_id' => $legacyPart->man_id,
                    ]
                );

                $successCount++;
            } catch (\Exception $e) {
                $failureCount++;
                $errorMessage = "Error seeding part {$legacyPart->part_id}: " . $e->getMessage();
                $this->command->error($errorMessage);
                Log::error($errorMessage);
                Log::error("Part data: " . json_encode($legacyPart));
            }
        }

        $this->command->info("Part seeding completed.");
        $this->command->info("Total parts: {$totalCount}");
        $this->command->info("Successfully seeded: {$successCount}");
        $this->command->error("Failed to seed: {$failureCount}");
    }
}
