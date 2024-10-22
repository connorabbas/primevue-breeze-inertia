<?php

namespace App\DataTransferObjects;

use Illuminate\Http\Request;

class PurchaseOrderFiltersDto
{
    public function __construct(
        public ?int $perPage = null,
        public ?int $currentPage = null,
        public ?string $number = null,
        public ?string $supplier = null,
        public ?string $status = null,
        public ?string $created_at = null,
        public ?string $total_cost = null,
        public ?string $user_name = null,
        public ?string $sortField = null,
        public ?string $sortDirection = 'asc',
    ) {}

    public static function fromDataTableRequest(Request $request): self
    {
        $filters = $request->input('filters');
        return new self(
            perPage: (int) $request->input('rows', 20),
            currentPage: (int) $request->input('page', 1),
            number: $filters['number']['value'] ?? null,
            supplier: $filters['supplier']['value'] ?? null,
            status: $filters['status']['value'] ?? null,
            created_at: $filters['created_at']['value'] ?? null,
            total_cost: $filters['total_cost']['value'] ?? null,
            user_name: $filters['user.name']['value'] ?? null,
            sortField: $request->input('sortField'),
            sortDirection: $request->input('sortOrder', 1) == 1 ? 'asc' : 'desc',
        );
    }
}
