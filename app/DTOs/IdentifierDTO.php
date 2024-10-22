<?php

namespace App\DTOs;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

/** @typescript */
class IdentifierDTO extends Data
{
    /**
     * @param Collection<int, IdentifierData> $identifiers
     */
    public function __construct(
        #[DataCollectionOf(IdentifierData::class)]
        public Collection $identifiers
    ) {}

    public static function fromArray(array $data): self
    {
        $identifiers = collect($data)->map(function ($item) {
            return new IdentifierData(
                $item['type'] ?? null,
                $item['value'] ?? null
            );
        });

        return new self($identifiers);
    }
}
