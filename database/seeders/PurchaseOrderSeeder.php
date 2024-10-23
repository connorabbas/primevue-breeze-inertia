<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderPart;
use App\Models\Supplier;
use App\Models\User;
use App\Enums\PurchaseOrderStatus;
use Illuminate\Database\Seeder;
use App\DTOs\SupplierAddressesDTO;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user
        $user = User::first();
        if (!$user) {
            $this->command->error('No users found in database. Please run UserSeeder first.');
            return;
        }

        // Get random suppliers
        $suppliers = Supplier::inRandomOrder()->take(3)->get();
        if ($suppliers->isEmpty()) {
            $this->command->error('No suppliers found in database. Please seed suppliers first.');
            return;
        }

        // Get random locations
        $locations = Location::where('type', 'warehouse')->inRandomOrder()->take(3)->get();
        if ($locations->isEmpty()) {
            $this->command->error('No warehouse locations found in database. Please seed locations first.');
            return;
        }

        // Get random parts
        $allParts = \App\Models\Part::with('supplier')->inRandomOrder()->take(10)->get();
        if ($allParts->isEmpty()) {
            $this->command->error('No parts found in database. Please seed parts first.');
            return;
        }

        // Create 5 purchase orders
        $statuses = [
            PurchaseOrderStatus::DRAFT,
            PurchaseOrderStatus::SUBMITTED,
            PurchaseOrderStatus::APPROVED,
            PurchaseOrderStatus::PARTIALLY_RECEIVED,
            PurchaseOrderStatus::FULLY_RECEIVED,
        ];

        foreach (range(1, 5) as $i) {
            $supplier = $suppliers->random();
            $location = $locations->random();

            // Filter parts for the selected supplier
            $supplierParts = $allParts->where('supplier_id', $supplier->id);
            if ($supplierParts->isEmpty()) {
                $supplierParts = $allParts; // Fallback to all parts if no supplier-specific parts found
            }

            // Create PO
            $po = PurchaseOrder::create([
                'supplier_id' => $supplier->id,
                'location_id' => $location->id,
                'status' => $statuses[$i - 1],
                'user_id' => $user->id,
                'tax_rate' => 8.25,
                'additional_costs' => rand(0, 100),
                'opened_at' => now()->subDays(rand(1, 30)),
                'closed_at' => in_array($statuses[$i - 1], [PurchaseOrderStatus::FULLY_RECEIVED, PurchaseOrderStatus::CLOSED]) ? now() : null,
                'addresses' => new SupplierAddressesDTO(
                    billTo: $location->addresses,
                    shipFrom: $supplier->addresses[0] ?? null,
                    shipTo: $location->addresses
                ),
            ]);

            // Add 2-4 random parts to each PO
            $numParts = rand(2, 4);
            $selectedParts = $supplierParts->random(min($numParts, $supplierParts->count()));

            foreach ($selectedParts as $part) {
                $quantity = rand(1, 10) * 5; // Random quantity in multiples of 5
                $unitCost = round(rand(500, 5000) / 100, 2); // Random cost between $5 and $50

                PurchaseOrderPart::create([
                    'purchase_order_id' => $po->id,
                    'part_id' => $part->id,
                    'quantity_ordered' => $quantity,
                    'unit_cost' => $unitCost,
                    'total_cost' => $quantity * $unitCost,
                    'quantity_received' => $po->status === PurchaseOrderStatus::FULLY_RECEIVED ? $quantity : ($po->status === PurchaseOrderStatus::PARTIALLY_RECEIVED ? floor($quantity / 2) : 0),
                ]);
            }

            // Recalculate the total cost
            $po->calculateTotals();
            $po->save();
        }
    }
}
