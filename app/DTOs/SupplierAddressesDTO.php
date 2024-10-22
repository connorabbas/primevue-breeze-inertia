<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

/** @typescript */
class SupplierAddressesDTO extends Data
{
    public function __construct(
        #[DataCollectionOf(AddressDTO::class)]
        public ?DataCollection $billTo = null,

        #[DataCollectionOf(AddressDTO::class)]
        public ?DataCollection $shipFrom = null,

        #[DataCollectionOf(AddressDTO::class)]
        public ?DataCollection $shipTo = null,

        #[DataCollectionOf(AddressDTO::class)]
        public ?DataCollection $returnTo = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            billTo: isset($data['billTo']) ? AddressDTO::collection($data['billTo']) : null,
            shipFrom: isset($data['shipFrom']) ? AddressDTO::collection($data['shipFrom']) : null,
            shipTo: isset($data['shipTo']) ? AddressDTO::collection($data['shipTo']) : null,
            returnTo: isset($data['returnTo']) ? AddressDTO::collection($data['returnTo']) : null
        );
    }

    public function toArray(): array
    {
        return [
            'billTo' => $this->billTo?->toArray(),
            'shipFrom' => $this->shipFrom?->toArray(),
            'shipTo' => $this->shipTo?->toArray(),
            'returnTo' => $this->returnTo?->toArray(),
        ];
    }
}
