<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

/** @typescript */
class LocationAddressesDTO extends Data
{
    public function __construct(
        #[DataCollectionOf(AddressDTO::class)]
        public ?DataCollection $billTo = null,

        #[DataCollectionOf(AddressDTO::class)]
        public ?DataCollection $shipFrom = null,

        #[DataCollectionOf(AddressDTO::class)]
        public ?DataCollection $shipTo = null,

        #[DataCollectionOf(AddressDTO::class)]
        public ?DataCollection $other = null
    ) {}

    public static function fromArray(array|string $data): self
    {
        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        return new self(
            billTo: isset($data['billTo']) ? AddressDTO::collection($data['billTo']) : null,
            shipFrom: isset($data['shipFrom']) ? AddressDTO::collection($data['shipFrom']) : null,
            shipTo: isset($data['shipTo']) ? AddressDTO::collection($data['shipTo']) : null,
            other: isset($data['other']) ? AddressDTO::collection($data['other']) : null
        );
    }

    public function toArray(): array
    {
        return [
            'billTo' => $this->billTo?->toArray(),
            'shipFrom' => $this->shipFrom?->toArray(),
            'shipTo' => $this->shipTo?->toArray(),
            'other' => $this->other?->toArray(),
        ];
    }
}
