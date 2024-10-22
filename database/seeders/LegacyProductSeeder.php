<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\DTOs\IdentifierDTO;
use App\DTOs\ReplenishmentDataDTO;
use Illuminate\Support\Facades\Log;

class LegacyProductSeeder extends Seeder
{
    public function run()
    {
        $legacyProducts = DB::connection('legacy')->table('product')->get();
        $totalCount = $legacyProducts->count();
        $successCount = 0;
        $failureCount = 0;

        $this->command->info("Starting to seed {$totalCount} products from legacy database.");

        foreach ($legacyProducts as $legacyProduct) {
            try {
                $identifiers = IdentifierDTo::fromArray([
                    'master_sku' => $legacyProduct->master_sku,
                    'gtin' => $legacyProduct->gtin,
                ]);

                $replenishmentData = new ReplenishmentDataDTO(
                    lead_days: 0, // Using 0 as a default value, adjust as needed
                    purchaseTerms: [
                        [
                            'minimum_quantity' => 1, // Assuming minimum quantity is 1
                            'cost_per_part' => $legacyProduct->product_cost,
                        ]
                    ]
                );

                Product::create([
                    'id' => $legacyProduct->product_id,
                    'name' => $legacyProduct->product_name,
                    'description' => $legacyProduct->product_desc,
                    'identifiers' => $identifiers,
                    'replenishment_data' => $replenishmentData,
                    'weight_oz' => $legacyProduct->weight_oz ?? 0,
                ]);

                $successCount++;
            } catch (\Exception $e) {
                $failureCount++;
                $errorMessage = "Error seeding product {$legacyProduct->product_id}: " . $e->getMessage();
                $this->command->error($errorMessage);
                Log::error($errorMessage);
            }
        }

        $this->command->info("Product seeding completed.");
        $this->command->info("Total products: {$totalCount}");
        $this->command->info("Successfully seeded: {$successCount}");
        $this->command->error("Failed to seed: {$failureCount}");
    }
}
