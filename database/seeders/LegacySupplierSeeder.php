<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;
use App\Models\Address;
use App\Data\AddressData;
use App\Enums\AddressType;
use App\Enums\PaymentTerm;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class LegacySupplierSeeder extends Seeder
{
    /**
     * Map legacy payment terms to new PaymentTerm enum
     */
    private function mapPaymentTerm(?string $legacyTerm): ?PaymentTerm
    {
        if (empty($legacyTerm)) {
            return null;
        }

        return match (strtolower(trim($legacyTerm))) {
            'net 30' => PaymentTerm::NET_30,
            'net 45' => PaymentTerm::NET_45,
            'net 60' => PaymentTerm::NET_60,
            'net 90' => PaymentTerm::NET_90,
            'credit card' => PaymentTerm::CREDIT_CARD,
            '30% inv/70% ship' => PaymentTerm::INV_30_SHIP_70,
            default => null,
        };
    }

    public function run()
    {
        $legacyLocations = DB::connection('legacy')->table('location')->get();
        $totalCount = $legacyLocations->count();
        $successCount = 0;
        $failureCount = 0;

        $this->command->info("Starting to seed suppliers and addresses from {$totalCount} legacy locations.");

        foreach ($legacyLocations as $location) {
            try {
                // Skip if it's not a supplier location and not Doner Industries
                if (!$location->supplier_id && $location->location_name !== 'Doner Industries, Inc.') {
                    continue;
                }

                // Get payment terms from legacy database if available
                $paymentTerms = null;
                if ($location->supplier_id) {
                    $legacySupplier = DB::connection('legacy')->table('supplier')
                        ->where('supplier_id', $location->supplier_id)
                        ->first();
                    if ($legacySupplier) {
                        $paymentTerms = $this->mapPaymentTerm($legacySupplier->payment_terms);
                    }
                }

                // Create or update supplier
                $supplier = Supplier::updateOrCreate(
                    ['id' => $location->supplier_id ?: 1], // Use ID 1 for Doner Industries
                    [
                        'name' => $location->location_name,
                        'account_number' => $location->supplier_id ? null : 'DI', // DI for Doner Industries
                        'payment_terms' => $paymentTerms,
                        'created_at' => $location->last_change_ts,
                        'updated_at' => $location->last_change_ts
                    ]
                );

                // Create address
                $address = Address::create([
                    'address_data' => AddressData::from([
                        'street1' => $location->address1,
                        'street2' => $location->address2,
                        'city' => $location->city,
                        'state' => $location->state_prov_code,
                        'postal_code' => $location->zip,
                        'country' => 'USA',
                        'phone' => $location->phone_nbr,
                        'email' => $location->email_address,
                        'contact_name' => ''
                    ])
                ]);

                // Attach address to supplier with appropriate types
                if ($location->bill_to_ind) {
                    $supplier->addresses()->attach($address->id, [
                        'address_type' => AddressType::BILL_TO,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    // Use billing address as return address
                    $supplier->addresses()->attach($address->id, [
                        'address_type' => AddressType::RETURN_TO,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                if ($location->ship_to_ind) {
                    $supplier->addresses()->attach($address->id, [
                        'address_type' => AddressType::SHIP_TO,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                // For suppliers, every address is a potential ship from
                if ($location->supplier_id || (!$location->buyer_ind && $location->location_name === 'Doner Industries, Inc.')) {
                    $supplier->addresses()->attach($address->id, [
                        'address_type' => AddressType::SHIP_FROM,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                $successCount++;
                $this->command->info("Successfully processed location ID: {$location->location_id} for supplier: {$location->location_name}");
            } catch (QueryException $e) {
                $failureCount++;
                $errorMessage = "Database error processing location ID {$location->location_id}: " . $e->getMessage();
                $this->command->error($errorMessage);
                Log::error($errorMessage);
                Log::error("SQL: " . $e->getSql());
                Log::error("Bindings: " . implode(', ', $e->getBindings()));
            } catch (\Exception $e) {
                $failureCount++;
                $errorMessage = "Error processing location ID {$location->location_id}: " . $e->getMessage();
                $this->command->error($errorMessage);
                Log::error($errorMessage);
                Log::error("Location data: " . json_encode($location));
            }
        }

        $this->command->info("Supplier and address seeding completed.");
        $this->command->info("Total locations processed: {$totalCount}");
        $this->command->info("Successfully processed: {$successCount}");
        $this->command->error("Failed to process: {$failureCount}");
    }
}
