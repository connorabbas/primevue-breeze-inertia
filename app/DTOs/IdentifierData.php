<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

/** @typescript */
class IdentifierData extends Data
{
    public function __construct(
        public ?string $type = null,
        public ?string $value = null
    ) {}
}
