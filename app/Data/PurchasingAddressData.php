<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class PurchaseOrderAddresses extends Data
{
    public function __construct(
        #[Optional]
        public AddressData $billTo,
        #[Optional]
        public AddressData $shipFrom,
        #[Optional]
        public AddressData $shipTo,
        #[Optional]
        public ?AddressData $returnTo,
    ) {
    }
}
