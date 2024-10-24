<?php

namespace Database\Seeders;

use App\Data\AddressData;
use App\Models\Supplier;
use App\Models\Address;
use App\Enums\AddressType;
use Illuminate\Database\Seeder;

class BuildDefaultSupplierSeeder extends Seeder
{
    public function run(): void
    {
        // Create the supplier first
        $supplier = Supplier::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Doner Industries, Inc.',
                'account_number' => 'DI',
                'created_at' => '2024-10-22 00:42:31',
                'updated_at' => '2024-10-22 00:42:31'
            ]
        );

        // Create the main office address data
        $mainOfficeData = AddressData::from([
            'street1' => '11233 W STONEBRIAR DR',
            'street2' => '',
            'city' => 'Bentonville',
            'state' => 'AR',
            'postal_code' => '72712',
            'country' => 'USA',
            'phone' => '479-802-9953',
            'email' => 'todd@donerindustries.com',
            'contact_name' => 'Todd Doner'
        ]);

        // Create the warehouse address data
        $warehouseData = AddressData::from([
            'street1' => '10636 W HWY 72 STE 601',
            'street2' => '',
            'city' => 'Bentonville',
            'state' => 'AR',
            'postal_code' => '72712',
            'country' => 'USA',
            'phone' => '479-802-9953',
            'email' => 'todd@donerindustries.com',
            'contact_name' => 'Todd Doner'
        ]);

        // Create the addresses in the database
        Address::updateOrCreate(
            [
                'supplier_id' => $supplier->id,
                'address_type' => AddressType::BILL_TO
            ],
            [
                'address_data' => $mainOfficeData
            ]
        );

        Address::updateOrCreate(
            [
                'supplier_id' => $supplier->id,
                'address_type' => AddressType::SHIP_FROM
            ],
            [
                'address_data' => $warehouseData
            ]
        );

        Address::updateOrCreate(
            [
                'supplier_id' => $supplier->id,
                'address_type' => AddressType::SHIP_TO
            ],
            [
                'address_data' => $mainOfficeData
            ]
        );

        Address::updateOrCreate(
            [
                'supplier_id' => $supplier->id,
                'address_type' => AddressType::RETURN_TO
            ],
            [
                'address_data' => $mainOfficeData
            ]
        );
    }
}
