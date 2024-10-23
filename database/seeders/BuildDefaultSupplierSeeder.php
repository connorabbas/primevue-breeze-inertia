<?php

namespace Database\Seeders;

use App\DTOs\AddressDTO;
use App\DTOs\SupplierAddressesDTO;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Spatie\LaravelData\DataCollection;

class BuildDefaultSupplierSeeder extends Seeder
{
    public function run(): void
    {
        // Create the main office address
        $warehouseAddress = new AddressDTO(
            street1: '10636 W HWY 72 STE 601',
            street2: '',
            city: 'Bentonville',
            state_prov_code: 'AR',
            zip: '72712',
            country: 'USA',
            phone_number: '479-802-9953',
            email_address: 'todd@donerindustries.com'
        );

        // Create the warehouse address
        $mainOfficeAddress = new AddressDTO(
            street1: '11233 W Stonebriar Dr',
            street2: '',
            city: 'Bentonville',
            state_prov_code: 'AR',
            zip: '72712',
            country: 'USA',
            phone_number: '479-802-9953',
            email_address: 'todd@donerindustries.com'
        );

        // Create SupplierAddressesDTO with both addresses
        $addresses = new SupplierAddressesDTO(
            billTo: new DataCollection(AddressDTO::class, [$mainOfficeAddress]),
            shipFrom: new DataCollection(AddressDTO::class,  [$warehouseAddress]),
            shipTo: new DataCollection(AddressDTO::class, [$mainOfficeAddress]),
            returnTo: null
        );

        // Create or update the supplier
        Supplier::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Doner Industries, Inc.',
                'account_number' => 'DI',
                'addresses' => $addresses,
                'created_at' => '2024-10-22 00:42:31',
                'updated_at' => '2024-10-22 00:42:31'
            ]
        );
    }
}
