<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/** @typescript */
class AddressDTO extends Data
{
    public function __construct(
        public ?string $street1 = null,
        public ?string $street2 = null,
        public ?string $city = null,
        public ?string $state = null,
        public ?string $postal_code = null,
        public ?string $country = null,
        public ?string $type = null,
        public ?string $address1 = null,
        public ?string $address2 = null,
        public ?string $state_prov_code = null,
        public ?string $zip = null,
        public ?string $phone_number = null,
        public ?string $email_address = null
    ) {
        // Map legacy field names to new ones if they exist
        $this->street1 = $this->street1 ?? $this->address1;
        $this->street2 = $this->street2 ?? $this->address2;
        $this->state = $this->state ?? $this->state_prov_code;
        $this->postal_code = $this->postal_code ?? $this->zip;
    }

    public static function collection(array $data): DataCollection
    {
        return new DataCollection(AddressDTO::class, array_map(function ($item) {
            if (is_array($item)) {
                // Handle select component value structure
                if (isset($item['value'])) {
                    $item = $item['value'];
                }

                return new self(
                    street1: $item['street1'] ?? $item['address1'] ?? null,
                    street2: $item['street2'] ?? $item['address2'] ?? null,
                    city: $item['city'] ?? null,
                    state: $item['state'] ?? $item['state_prov_code'] ?? null,
                    postal_code: $item['postal_code'] ?? $item['zip'] ?? null,
                    country: $item['country'] ?? null,
                    type: $item['type'] ?? null,
                    address1: $item['address1'] ?? $item['street1'] ?? null,
                    address2: $item['address2'] ?? $item['street2'] ?? null,
                    state_prov_code: $item['state_prov_code'] ?? $item['state'] ?? null,
                    zip: $item['zip'] ?? $item['postal_code'] ?? null,
                    phone_number: $item['phone_number'] ?? null,
                    email_address: $item['email_address'] ?? null
                );
            } elseif ($item instanceof self) {
                return $item;
            } else {
                throw new \InvalidArgumentException('Invalid item type in AddressDTO collection');
            }
        }, $data));
    }

    public function toArray(): array
    {
        return array_filter([
            'street1' => $this->street1,
            'street2' => $this->street2,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'type' => $this->type,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'state_prov_code' => $this->state_prov_code,
            'zip' => $this->zip,
            'phone_number' => $this->phone_number,
            'email_address' => $this->email_address
        ], function ($value) {
            return !is_null($value);
        });
    }
}
