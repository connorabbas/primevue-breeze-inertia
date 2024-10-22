<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;
use App\DTOs\SupplierAddressesDTO;
use App\DTOs\AddressDTO;
use Illuminate\Support\Facades\Log;
use Spatie\LaravelData\DataCollection;
use Illuminate\Database\QueryException;

class LegacySupplierSeeder extends Seeder
{
    public function run()
    {
        $legacySuppliers = DB::connection('legacy')->table('supplier')->get();
        $totalCount = $legacySuppliers->count();
        $successCount = 0;
        $failureCount = 0;

        $this->command->info("Starting to seed {$totalCount} suppliers from legacy database.");

        foreach ($legacySuppliers as $legacySupplier) {
            try {
                $addresses = $this->getSupplierAddresses($legacySupplier->supplier_id);

                Supplier::create([
                    'id' => $legacySupplier->supplier_id,
                    'name' => $legacySupplier->supplier_name,
                    'account_number' => $legacySupplier->account,
                    'payment_terms' => $legacySupplier->payment_terms,
                    'lead_time_days' => null, // This field is not in the legacy schema
                    'free_shipping_threshold_usd' => $legacySupplier->free_ship_min,
                    'contact' => json_encode([
                        'website' => $legacySupplier->website,
                        'phone' => $legacySupplier->phone,
                        'fax' => $legacySupplier->fax,
                    ]),
                    'addresses' => $addresses,
                ]);

                $successCount++;
                $this->command->info("Successfully seeded supplier ID: {$legacySupplier->supplier_id}");
            } catch (QueryException $e) {
                $failureCount++;
                $errorMessage = "Database error seeding supplier ID {$legacySupplier->supplier_id}: " . $e->getMessage();
                $this->command->error($errorMessage);
                Log::error($errorMessage);
                Log::error("SQL: " . $e->getSql());
                Log::error("Bindings: " . implode(', ', $e->getBindings()));
            } catch (\Exception $e) {
                $failureCount++;
                $errorMessage = "Error seeding supplier ID {$legacySupplier->supplier_id}: " . $e->getMessage();
                $this->command->error($errorMessage);
                Log::error($errorMessage);
                Log::error("Supplier data: " . json_encode($legacySupplier));
            }
        }

        $this->command->info("Supplier seeding completed.");
        $this->command->info("Total suppliers: {$totalCount}");
        $this->command->info("Successfully seeded: {$successCount}");
        $this->command->error("Failed to seed: {$failureCount}");
    }

    private function getSupplierAddresses($supplierId)
    {
        $locations = DB::connection('legacy')->table('location')
            ->where('supplier_id', $supplierId)
            ->get();

        $billTo = [];
        $shipFrom = [];
        $shipTo = [];
        $returnTo = [];

        foreach ($locations as $location) {
            try {
                $address = new AddressDTO(
                    address1: $location->address1,
                    address2: $location->address2,
                    city: $location->city,
                    state_prov_code: $location->state_prov_code,
                    zip: $location->zip,
                    phone_number: $location->phone_nbr,
                    email_address: $location->email_address
                );

                if ($location->bill_to_ind) {
                    $billTo[] = $address;
                }
                if ($location->ship_to_ind) {
                    $shipTo[] = $address;
                }
                // Assume all locations can be ship from and return to
                $shipFrom[] = $address;
                $returnTo[] = $address;
            } catch (\Exception $e) {
                $errorMessage = "Error creating address for supplier ID {$supplierId}, location ID {$location->location_id}: " . $e->getMessage();
                $this->command->error($errorMessage);
                Log::error($errorMessage);
                Log::error("Location data: " . json_encode($location));
            }
        }

        if (empty($billTo) && empty($shipFrom) && empty($shipTo) && empty($returnTo)) {
            Log::warning("No valid addresses found for supplier ID {$supplierId}");
        }

        return new SupplierAddressesDTO(
            billTo: new DataCollection(AddressDTO::class, $billTo),
            shipFrom: new DataCollection(AddressDTO::class, $shipFrom),
            shipTo: new DataCollection(AddressDTO::class, $shipTo),
            returnTo: new DataCollection(AddressDTO::class, $returnTo)
        );
    }
}
