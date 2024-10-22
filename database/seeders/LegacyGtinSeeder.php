<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Gtin;
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
                Gtin::create([
                    'gtin' => $legacyGtin->gtin_nbr,
                    'status' => $this->mapStatus($legacyGtin->status_id),
                    'lease_end_date' => $legacyGtin->last_change_ts !== '0000-00-00 00:00:00' ? $legacyGtin->last_change_ts : null,
                    'product_id' => $legacyGtin->seller_id, // We're using seller_id as product_id for now
                ]);

                $successCount++;
            } catch (\Exception $e) {
                $failureCount++;
                $errorMessage = "Error seeding GTIN {$legacyGtin->gtin_nbr}: " . $e->getMessage();
                $this->command->error($errorMessage);
                Log::error($errorMessage);
            }
        }

        $this->command->info("GTIN seeding completed.");
        $this->command->info("Total GTINs: {$totalCount}");
        $this->command->info("Successfully seeded: {$successCount}");
        $this->command->error("Failed to seed: {$failureCount}");
    }

    private function mapStatus($legacyStatus)
    {
        // Map legacy status to new status
        // Adjust this mapping according to your new status structure
        $statusMap = [
            1 => 'active',
            2 => 'inactive',
            // Add more mappings as needed
        ];

        return $statusMap[$legacyStatus] ?? 'unknown';
    }
}
