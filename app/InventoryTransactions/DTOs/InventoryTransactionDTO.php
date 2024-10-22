<?php

namespace App\InventoryTransactions\DTOs;

use App\InventoryTransactions\Enums\TransactionType;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class InventoryTransactionDTO extends Data
{
    public function __construct(
        public TransactionType $transaction_type,
        public int $quantity,
        #[MapInputName('inventoryable_type')]
        public string $inventoryableType,
        #[MapInputName('inventoryable_id')]
        public int $inventoryableId,
        #[MapInputName('from_location_id')]
        public ?int $fromLocationId = null,
        #[MapInputName('to_location_id')]
        public ?int $toLocationId = null,
        public ?string $reason = null,
        #[MapInputName('user_id')]
        public ?int $userId = null,
        #[MapInputName('reference_type')]
        public ?string $referenceType = null,
        #[MapInputName('reference_id')]
        public ?int $referenceId = null
    ) {}

    public static function rules(): array
    {
        return [
            'transaction_type' => ['required', 'enum:'.TransactionType::class],
            'quantity' => ['required', 'integer', 'min:1'],
            'inventoryableType' => ['required', 'string'],
            'inventoryableId' => ['required', 'integer'],
            'fromLocationId' => ['nullable', 'integer', 'exists:locations,id'],
            'toLocationId' => ['nullable', 'integer', 'exists:locations,id'],
            'reason' => ['nullable', 'string'],
            'userId' => ['nullable', 'integer', 'exists:users,id'],
            'referenceType' => ['nullable', 'string'],
            'referenceId' => ['nullable', 'integer'],
        ];
    }
}
