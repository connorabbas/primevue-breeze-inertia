<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Gtin;
use App\Models\Brand;
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
                // Create replenishment data
                $replenishmentData = new ReplenishmentDataDTO(
                    lead_days: 0,
                    purchaseTerms: [
                        [
                            'minimum_quantity' => $legacyProduct->package_qty,
                            'cost_per_part' => $legacyProduct->product_cost
                        ]
                    ]
                );

                // Create identifiers including brand and manufacturer
                $identifiers = IdentifierDTO::fromArray([
                    [
                        'type' => 'brand_id',
                        'value' => $legacyProduct->brand_id
                    ],
                    [
                        'type' => 'manufacturer_id',
                        'value' => $legacyProduct->man_id
                    ]
                ]);

                // Create or update product
                $product = Product::updateOrCreate(
                    ['id' => $legacyProduct->product_id],
                    [
                        'name' => $legacyProduct->product_name,
                        'sku' => $legacyProduct->master_sku,
                        'description' => $legacyProduct->product_desc,
                        'identifiers' => $identifiers,
                        'replenishment_data' => $replenishmentData,
                        'weight_oz' => $legacyProduct->ship_weight_oz,
                    ]
                );

                // Handle GTIN
                if (!empty($legacyProduct->gtin)) {
                    // Check if GTIN exists in legacy gtin table
                    $legacyGtin = DB::connection('legacy')
                        ->table('gtin')
                        ->where('gtin_nbr', $legacyProduct->gtin)
                        ->first();

                    $gtinStatus = $legacyGtin ? $this->mapGtinStatus($legacyGtin->status_id) : 'active';
                    $leaseEndDate = $legacyGtin && $legacyGtin->last_change_ts !== '0000-00-00 00:00:00'
                        ? $legacyGtin->last_change_ts
                        : null;

                    Gtin::updateOrCreate(
                        ['gtin' => $legacyProduct->gtin],
                        [
                            'product_id' => $product->id,
                            'status' => $gtinStatus,
                            'lease_end_date' => $leaseEndDate
                        ]
                    );
                }

                // Handle dimensions
                if ($legacyProduct->ship_length_in || $legacyProduct->ship_width_in || $legacyProduct->ship_height_in) {
                    $product->dimensions()->create([
                        'length' => $legacyProduct->ship_length_in,
                        'width' => $legacyProduct->ship_width_in,
                        'height' => $legacyProduct->ship_height_in,
                        'unit' => 'in'
                    ]);
                }

                $successCount++;
                $this->command->info("Successfully processed product: {$legacyProduct->master_sku}");
            } catch (\Exception $e) {
                $failureCount++;
                $errorMessage = "Error seeding product {$legacyProduct->product_id} ({$legacyProduct->master_sku}): " . $e->getMessage();
                $this->command->error($errorMessage);
                Log::error($errorMessage);
                Log::error("Product data: " . json_encode($legacyProduct));
            }
        }

        $this->command->info("Product seeding completed.");
        $this->command->info("Total products: {$totalCount}");
        $this->command->info("Successfully seeded: {$successCount}");
        $this->command->error("Failed to seed: {$failureCount}");
    }

    private function mapGtinStatus(int $statusId): string
    {
        return match ($statusId) {
            1 => 'active',
            2 => 'inactive',
            default => 'unknown'
        };
    }
}
