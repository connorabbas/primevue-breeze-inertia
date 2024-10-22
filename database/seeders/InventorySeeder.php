<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Location;
use App\Models\Part;
use App\Models\Product;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding inventory for Products...');
        $this->seedRandomProductInventory();

        $this->command->info('Seeding inventory for Parts...');
        $this->seedRandomPartInventory();
    }

    /**
     * Seed inventory entries for Products.
     */
    protected function seedRandomProductInventory()
    {
        // Create 10 products
        Product::factory()->count(10)->create();
        $products = Product::all();
        $locations = Location::all();

        // Calculate maximum unique combinations
        $maxEntries = $products->count() * $locations->count();

        // Inform the user about the maximum entries
        $this->command->info("Maximum unique inventory entries possible for Products: {$maxEntries}");

        // Shuffle products and locations for randomness
        $shuffledProducts = $products->shuffle();
        $shuffledLocations = $locations->shuffle();

        $createdEntries = 0;

        foreach ($shuffledProducts as $product) {
            foreach ($shuffledLocations as $location) {
                // Check if the combination already exists
                $exists = Inventory::where('inventoryable_type', Product::class)
                    ->where('inventoryable_id', $product->id)
                    ->where('location_id', $location->id)
                    ->exists();

                if (! $exists) {
                    Inventory::create([
                        'location_id' => $location->id,
                        'inventoryable_id' => $product->id,
                        'inventoryable_type' => Product::class,
                        'quantity_onhand' => rand(0, 1000),
                        'quantity_intransit' => rand(0, 50),
                        'quantity_backordered' => rand(0, 50),
                        'quantity_allocated' => rand(0, 50),
                        'quantity_reserved' => rand(0, 100),
                    ]);

                    $createdEntries++;
                    $this->command->info("Created inventory for Product ID {$product->id} at Location ID {$location->id}");
                }
            }
        }

        $this->command->info("Total unique inventory entries created for Products: {$createdEntries}");
    }

    /**
     * Seed inventory entries for Parts.
     */
    protected function seedRandomPartInventory()
    {
        // Create 10 parts using the provided PartFactory
        Part::factory()->count(10)->create();
        $parts = Part::all();
        $locations = Location::all();

        // Calculate maximum unique combinations
        $maxEntries = $parts->count() * $locations->count();

        // Inform the user about the maximum entries
        $this->command->info("Maximum unique inventory entries possible for Parts: {$maxEntries}");

        // Shuffle parts and locations for randomness
        $shuffledParts = $parts->shuffle();
        $shuffledLocations = $locations->shuffle();

        $createdEntries = 0;

        foreach ($shuffledParts as $part) {
            foreach ($shuffledLocations as $location) {
                // Check if the combination already exists
                $exists = Inventory::where('inventoryable_type', Part::class)
                    ->where('inventoryable_id', $part->id)
                    ->where('location_id', $location->id)
                    ->exists();

                if (! $exists) {
                    Inventory::create([
                        'location_id' => $location->id,
                        'inventoryable_id' => $part->id,
                        'inventoryable_type' => Part::class,
                        'quantity_onhand' => rand(0, 1000),
                        'quantity_intransit' => rand(0, 50),
                        'quantity_backordered' => rand(0, 50),
                        'quantity_allocated' => rand(0, 50),
                        'quantity_reserved' => rand(0, 100),
                    ]);

                    $createdEntries++;
                    $this->command->info("Created inventory for Part ID {$part->id} at Location ID {$location->id}");
                }
            }
        }

        $this->command->info("Total unique inventory entries created for Parts: {$createdEntries}");
    }
}
