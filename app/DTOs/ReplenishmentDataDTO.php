<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;

/** @typescript */
class ReplenishmentDataDTO extends Data
{
    public function __construct(
        public int $lead_days,
        #[MapInputName('purchase_terms')]
        /** @var array<int, array{minimum_quantity: int, cost_per_part: float}> */
        public array $purchaseTerms
    ) {
    }
}
