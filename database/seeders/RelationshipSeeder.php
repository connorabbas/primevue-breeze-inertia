<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Part;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Manufacturer;
use App\Models\BillOfMaterial;
use App\Models\Dimension;
use App\Models\Gtin;
use App\Models\Location;
use App\Models\Address;
use App\Data\AddressData;
use App\Enums\AddressType;
use App\Enums\DimensionType;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class RelationshipSeeder extends Seeder
{
    public function run()
    {
        $this->command->info("Starting to set relationships...");

        $this->setManufacturerRelationships();
        $this->setSupplierRelationships();
        $this->setLocationRelationships();
        $this->setPartRelationships();
        $this->setProductRelationships();
        $this->setBillOfMaterialRelationships();
        $this->setGtinRelationships();
        $this->setDimensions();

        $this->command->info("Relationship seeding completed.");
    }

    private function setManufacturerRelationships()
    {
        // ... (keep existing code)
    }

    private function setSupplierRelationships()
    {
        $this->command->info("Setting supplier relationships...");

        $legacySuppliers = DB::connection('legacy')->table('supplier')->get();
        $successCount = 0;
        $failureCount = 0;

        foreach ($legacySuppliers as $legacySupplier) {
            try {
                // Create or update supplier
                $supplier = Supplier::updateOrCreate(
                    ['id' => $legacySupplier->supplier_id],
                    [
                        'name' => $legacySupplier->supplier_name,
                        'account_number' => $legacySupplier->account,
                        'payment_terms' => $legacySupplier->payment_terms,
                        'free_shipping_threshold_usd' => $legacySupplier->free_ship_min,
                        'contact' => json_encode([
                            'website' => $legacySupplier->website,
                            'phone' => $legacySupplier->phone,
                            'fax' => $legacySupplier->fax,
                        ])
                    ]
                );

                // Get all locations for this supplier
                $supplierLocations = DB::connection('legacy')
                    ->table('location')
                    ->where('supplier_id', $legacySupplier->supplier_id)
                    ->get();

                foreach ($supplierLocations as $location) {
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

                    // Attach address with appropriate types
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
                    $supplier->addresses()->attach($address->id, [
                        'address_type' => AddressType::SHIP_FROM,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                $successCount++;
            } catch (QueryException $e) {
                $failureCount++;
                Log::error("Database error setting supplier relationships for supplier ID {$legacySupplier->supplier_id}: " . $e->getMessage());
            } catch (\Exception $e) {
                $failureCount++;
                Log::error("Error setting supplier relationships for supplier ID {$legacySupplier->supplier_id}: " . $e->getMessage());
                Log::error("Supplier data: " . json_encode($legacySupplier));
            }
        }

        $this->command->info("Supplier relationships: {$successCount} succeeded, {$failureCount} failed.");
    }

    private function setLocationRelationships()
    {
        $this->command->info("Setting location relationships...");

        $legacyLocations = DB::connection('legacy')->table('location')->get();
        $successCount = 0;
        $failureCount = 0;

        foreach ($legacyLocations as $legacyLocation) {
            try {
                $locationType = $this->getLocationType($legacyLocation);

                Location::updateOrCreate(
                    ['id' => $legacyLocation->location_id],
                    [
                        'name' => $legacyLocation->location_name,
                        'type' => $locationType['type'],
                        'virtual_type' => $locationType['virtual_type'] ?? null,
                        'supplier_id' => $legacyLocation->supplier_id ?: null,
                    ]
                );
                $successCount++;
            } catch (QueryException $e) {
                $failureCount++;
                Log::error("Database error setting location relationships for location ID {$legacyLocation->location_id}: " . $e->getMessage());
            } catch (\Exception $e) {
                $failureCount++;
                Log::error("Error setting location relationships for location ID {$legacyLocation->location_id}: " . $e->getMessage());
                Log::error("Location data: " . json_encode($legacyLocation));
            }
        }

        $this->command->info("Location relationships: {$successCount} succeeded, {$failureCount} failed.");
    }

    private function setPartRelationships()
    {
        $this->command->info("Setting part relationships...");

        $legacyParts = DB::connection('legacy')->table('part')->get();
        $successCount = 0;
        $failureCount = 0;

        foreach ($legacyParts as $legacyPart) {
            try {
                Part::updateOrCreate(
                    ['id' => $legacyPart->part_id],
                    [
                        'part_number' => $legacyPart->part_nbr,
                        'quantity' => $legacyPart->part_qty,
                        'description' => $legacyPart->part_desc,
                        'identifiers' => json_encode([
                            'fda_listing_nbr' => $legacyPart->fda_listing_nbr,
                            'hsc_code' => $legacyPart->hsc_code,
                            'hsc_desc' => $legacyPart->hsc_desc,
                        ]),
                        'replenishment_data' => json_encode([
                            'min_order_qty1' => $legacyPart->min_order_qty1,
                            'min_order_cost1' => $legacyPart->min_order_cost1,
                            'min_order_qty2' => $legacyPart->min_order_qty2,
                            'min_order_cost2' => $legacyPart->min_order_cost2,
                            'min_order_qty3' => $legacyPart->min_order_qty3,
                            'min_order_cost3' => $legacyPart->min_order_cost3,
                            'lead_days' => $legacyPart->lead_days,
                        ]),
                        'supplier_id' => $legacyPart->supplier_id,
                        'manufacturer_id' => $legacyPart->man_id,
                    ]
                );
                $successCount++;
            } catch (QueryException $e) {
                $failureCount++;
                Log::error("Database error setting part relationships for part ID {$legacyPart->part_id}: " . $e->getMessage());
                Log::error("SQL: " . $e->getSql());
                Log::error("Bindings: " . implode(', ', $e->getBindings()));
            } catch (\Exception $e) {
                $failureCount++;
                Log::error("Error setting part relationships for part ID {$legacyPart->part_id}: " . $e->getMessage());
                Log::error("Part data: " . json_encode($legacyPart));
            }
        }

        $this->command->info("Part relationships: {$successCount} succeeded, {$failureCount} failed.");
    }

    private function setProductRelationships()
    {
        $this->command->info("Setting product relationships...");

        $legacyProducts = DB::connection('legacy')->table('product')->get();
        $successCount = 0;
        $failureCount = 0;

        foreach ($legacyProducts as $legacyProduct) {
            try {
                Product::updateOrCreate(
                    ['id' => $legacyProduct->product_id],
                    [
                        'name' => $legacyProduct->product_name,
                        'description' => $legacyProduct->product_desc,
                        'identifiers' => json_encode([
                            'master_sku' => $legacyProduct->master_sku,
                            'gtin' => $legacyProduct->gtin,
                        ]),
                        'replenishment_data' => json_encode([
                            'product_cost' => $legacyProduct->product_cost,
                            'package_qty' => $legacyProduct->package_qty,
                            'map_price' => $legacyProduct->map_price,
                            'min_markup_pct' => $legacyProduct->min_markup_pct,
                            'max_markup_pct' => $legacyProduct->max_markup_pct,
                            'min_markup_amt' => $legacyProduct->min_markup_amt,
                            'max_markup_amt' => $legacyProduct->max_markup_amt,
                        ]),
                        'weight_oz' => $legacyProduct->ship_weight_oz,
                    ]
                );
                $successCount++;
            } catch (QueryException $e) {
                $failureCount++;
                Log::error("Database error setting product relationships for product ID {$legacyProduct->product_id}: " . $e->getMessage());
                Log::error("SQL: " . $e->getSql());
                Log::error("Bindings: " . implode(', ', $e->getBindings()));
            } catch (\Exception $e) {
                $failureCount++;
                Log::error("Error setting product relationships for product ID {$legacyProduct->product_id}: " . $e->getMessage());
                Log::error("Product data: " . json_encode($legacyProduct));
            }
        }

        $this->command->info("Product relationships: {$successCount} succeeded, {$failureCount} failed.");
    }

    private function setBillOfMaterialRelationships()
    {
        $this->command->info("Setting bill of material relationships...");

        $legacyProductParts = DB::connection('legacy')->table('product_part')->get();
        $successCount = 0;
        $failureCount = 0;

        foreach ($legacyProductParts as $legacyProductPart) {
            try {
                BillOfMaterial::updateOrCreate(
                    [
                        'product_id' => $legacyProductPart->product_id,
                        'part_id' => $legacyProductPart->part_id,
                    ],
                    ['quantity_required' => $legacyProductPart->part_qty]
                );
                $successCount++;
            } catch (QueryException $e) {
                $failureCount++;
                Log::error("Database error setting bill of material relationships for product ID {$legacyProductPart->product_id} and part ID {$legacyProductPart->part_id}: " . $e->getMessage());
                Log::error("SQL: " . $e->getSql());
                Log::error("Bindings: " . implode(', ', $e->getBindings()));
            } catch (\Exception $e) {
                $failureCount++;
                Log::error("Error setting bill of material relationships for product ID {$legacyProductPart->product_id} and part ID {$legacyProductPart->part_id}: " . $e->getMessage());
                Log::error("Product Part data: " . json_encode($legacyProductPart));
            }
        }

        $this->command->info("Bill of Material relationships: {$successCount} succeeded, {$failureCount} failed.");
    }

    private function setGtinRelationships()
    {
        $this->command->info("Setting GTIN relationships...");

        $legacyGtins = DB::connection('legacy')->table('gtin')->get();
        $successCount = 0;
        $failureCount = 0;

        foreach ($legacyGtins as $legacyGtin) {
            try {
                Gtin::updateOrCreate(
                    ['gtin' => $legacyGtin->gtin_nbr],
                    [
                        'status' => $this->mapStatus($legacyGtin->status_id),
                        'lease_end_date' => $legacyGtin->last_change_ts !== '0000-00-00 00:00:00' ? $legacyGtin->last_change_ts : null,
                        'product_id' => $legacyGtin->seller_id,
                    ]
                );
                $successCount++;
            } catch (QueryException $e) {
                $failureCount++;
                Log::error("Database error setting GTIN relationships for GTIN {$legacyGtin->gtin_nbr}: " . $e->getMessage());
                Log::error("SQL: " . $e->getSql());
                Log::error("Bindings: " . implode(', ', $e->getBindings()));
            } catch (\Exception $e) {
                $failureCount++;
                Log::error("Error setting GTIN relationships for GTIN {$legacyGtin->gtin_nbr}: " . $e->getMessage());
                Log::error("GTIN data: " . json_encode($legacyGtin));
            }
        }

        $this->command->info("GTIN relationships: {$successCount} succeeded, {$failureCount} failed.");
    }

    private function setDimensions()
    {
        $this->setProductDimensions();
        $this->setPartDimensions();
    }

    private function setProductDimensions()
    {
        $this->command->info("Setting product dimensions...");

        $legacyProducts = DB::connection('legacy')->table('product')->get();
        $successCount = 0;
        $failureCount = 0;

        foreach ($legacyProducts as $legacyProduct) {
            try {
                $product = Product::find($legacyProduct->product_id);
                if ($product && $legacyProduct->ship_length_in && $legacyProduct->ship_width_in && $legacyProduct->ship_height_in) {
                    $this->attachDimension($product, $legacyProduct->ship_length_in, $legacyProduct->ship_width_in, $legacyProduct->ship_height_in, DimensionType::PRODUCT);
                    $successCount++;
                } else {
                    $failureCount++;
                    Log::warning("Product not found or missing dimensions for product ID {$legacyProduct->product_id}");
                }
            } catch (QueryException $e) {
                $failureCount++;
                Log::error("Database error setting dimensions for product ID {$legacyProduct->product_id}: " . $e->getMessage());
                Log::error("SQL: " . $e->getSql());
                Log::error("Bindings: " . implode(', ', $e->getBindings()));
            } catch (\Exception $e) {
                $failureCount++;
                Log::error("Error setting dimensions for product ID {$legacyProduct->product_id}: " . $e->getMessage());
                Log::error("Product data: " . json_encode($legacyProduct));
            }
        }

        $this->command->info("Product dimensions: {$successCount} succeeded, {$failureCount} failed.");
    }

    private function setPartDimensions()
    {
        $this->command->info("Setting part dimensions...");

        $legacyParts = DB::connection('legacy')->table('part')->get();
        $successCount = 0;
        $failureCount = 0;

        foreach ($legacyParts as $legacyPart) {
            try {
                $part = Part::find($legacyPart->part_id);
                if ($part && isset($legacyPart->length) && isset($legacyPart->width) && isset($legacyPart->height)) {
                    $this->attachDimension($part, $legacyPart->length, $legacyPart->width, $legacyPart->height, DimensionType::INDIVIDUAL_UNIT);
                    $successCount++;
                } else {
                    $failureCount++;
                    Log::warning("Part not found or missing dimensions for part ID {$legacyPart->part_id}");
                }
            } catch (QueryException $e) {
                $failureCount++;
                Log::error("Database error setting dimensions for part ID {$legacyPart->part_id}: " . $e->getMessage());
                Log::error("SQL: " . $e->getSql());
                Log::error("Bindings: " . implode(', ', $e->getBindings()));
            } catch (\Exception $e) {
                $failureCount++;
                Log::error("Error setting dimensions for part ID {$legacyPart->part_id}: " . $e->getMessage());
                Log::error("Part data: " . json_encode($legacyPart));
            }
        }

        $this->command->info("Part dimensions: {$successCount} succeeded, {$failureCount} failed.");
    }

    private function attachDimension($model, $length, $width, $height, $type)
    {
        $dimension = Dimension::firstOrCreate(
            [
                'length' => $length,
                'width' => $width,
                'height' => $height,
                'unit' => 'in',
                'type' => $type,
            ]
        );

        $model->dimensions()->syncWithoutDetaching([
            $dimension->id => ['dimensionable_type' => get_class($model)]
        ]);
    }

    private function mapStatus($legacyStatus)
    {
        $statusMap = [
            1 => 'active',
            2 => 'inactive',
        ];

        return $statusMap[$legacyStatus] ?? 'unknown';
    }

    private function getLocationType($legacyLocation)
    {
        if ($legacyLocation->buyer_ind) {
            return [
                'type' => 'virtual',
                'virtual_type' => 'bill_to'
            ];
        } elseif ($legacyLocation->supplier_id) {
            return ['type' => 'supplier'];
        } elseif ($legacyLocation->bill_to_ind) {
            return [
                'type' => 'virtual',
                'virtual_type' => 'bill_to'
            ];
        } elseif ($legacyLocation->ship_to_ind) {
            return [
                'type' => 'virtual',
                'virtual_type' => 'ship_to'
            ];
        } else {
            return ['type' => 'virtual'];
        }
    }
}
