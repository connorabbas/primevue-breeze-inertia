<?php

namespace App\DataTransferObjects;

use Illuminate\Http\Request;

class UserFiltersDto
{
    public function __construct(
        public ?int $perPage = null,
        public ?int $currentPage = null,
        public ?string $name = null,
        public ?string $email = null,
        public ?string $sortField = null,
        public ?string $sortDirection = 'asc',
    ) {
    }

    public static function fromDataTableRequest(Request $request): self
    {
        $filters = $request->input('filters');
        return new self(
            perPage: (int) $request->input('rows', 20),
            currentPage: (int) $request->input('page', 1),
            name: $filters['name']['value'] ?? null,
            email: $filters['email']['value'] ?? null,
            sortField: $request->input('sortField'),
            sortDirection: $request->input('sortOrder', 1) == 1 ? 'asc' : 'desc',
        );
    }
}
