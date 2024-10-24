<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Gtin;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class LegacyGtinSeeder extends Seeder
{
    public function run()
    {
        $legacyGtins = DB::connection('legacy')->table('gtin')->get();
        $totalCount = $legacyGtins->count();
        $successCount = 0;
        $failureCount = 0;

        $this->command->info("Starting to seed {$totalCount} GTINs from legacy database.");

        foreach ($legacyGtins as $legacyGtin) {
            try {
                // Find product with this GTIN
                $product = Product::whereHas('gtin', function ($query) use ($legacyGtin) {
                    $query->where('gtin', $legacyGtin->gtin_nbr);
                })->first();

                if ($product) {
                    Gtin::updateOrCreate(
                        ['gtin' => $legacyGtin->gtin_nbr],
                        [
                            'product_id' => $product->id,
                            'status' => $this->mapStatus($legacyGtin->status_id),
                            'lease_end_date' => $legacyGtin->last_change_ts !== '0000-00-00 00:00:00'
                                ? $legacyGtin->last_change_ts
                                : null
                        ]
                    );
                    $successCount++;
                } else {
                    Log::warning("No product found for GTIN {$legacyGtin->gtin_nbr}");
                    $failureCount++;
                }
            } catch (\Exception $e) {
                $failureCount++;
                $errorMessage = "Error seeding GTIN {$legacyGtin->gtin_nbr}: " . $e->getMessage();
                $this->command->error($errorMessage);
                Log::error($errorMessage);
                Log::error("GTIN data: " . json_encode($legacyGtin));
            }
        }

        $this->command->info("GTIN seeding completed.");
        $this->command->info("Total GTINs: {$totalCount}");
        $this->command->info("Successfully seeded: {$successCount}");
        $this->command->error("Failed to seed: {$failureCount}");
    }

    private function mapStatus(int $statusId): string
    {
        return match ($statusId) {
            1 => 'active',
            2 => 'inactive',
            default => 'unknown'
        };
    }
}
