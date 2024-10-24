<?php

namespace App\InventoryTransactions\Validators;

use App\InventoryTransactions\DTOs\InventoryTransactionDTO;
use App\InventoryTransactions\Enums\TransactionType;
use App\InventoryTransactions\Exceptions\InvalidTransactionData;
use App\Models\Inventory;
use App\Models\Location;
use Illuminate\Database\Eloquent\Model;

class InventoryTransactionValidator
{
    public function validate(array|InventoryTransactionDTO $data): void
    {
        $dto = $data instanceof InventoryTransactionDTO ? $data : InventoryTransactionDTO::from($data);

        $errors = array_merge(
            $this->validateQuantity($dto->quantity, $dto->transaction_type),
            $this->validateInventoryable($dto->inventoryableType, $dto->inventoryableId),
            $this->validateLocations($dto),
            $this->validateInventoryLevels($dto)
        );

        if (! empty($errors)) {
            throw new InvalidTransactionData(implode(', ', $errors));
        }
    }

    private function validateQuantity(int $quantity, TransactionType $transactionType): array
    {
        $errors = [];

        if ($transactionType !== TransactionType::ADJUSTMENT && $transactionType !== TransactionType::CYCLE_COUNT && $quantity <= 0) {
            $errors[] = "Invalid quantity: {$quantity}. Must be greater than 0 for {$transactionType->value}.";
        }

        return $errors;
    }

    private function validateInventoryable(string $type, int $id): array
    {
        $errors = [];

        if (!class_exists($type)) {
            $errors[] = "Invalid inventoryable type: {$type}";
            return $errors;
        }

        $model = new $type();
        if (! $model instanceof Model) {
            $errors[] = "Invalid inventoryable type: {$type}. Must be an Eloquent model.";
        }

        $inventoryable = $model->find($id);
        if (! $inventoryable) {
            $errors[] = "Invalid inventoryable id: {$id} for type: {$type}";
        }

        return $errors;
    }

    private function validateLocations(InventoryTransactionDTO $dto): array
    {
        $errors = [];

        switch ($dto->transaction_type) {
            case TransactionType::TRANSFER:
                $errors = array_merge(
                    $errors,
                    $this->validateLocation($dto->fromLocationId, 'fromLocationId'),
                    $this->validateLocation($dto->toLocationId, 'toLocationId')
                );
                if ($dto->fromLocationId === $dto->toLocationId) {
                    $errors[] = 'From and To locations must be different for TRANSFER.';
                }
                break;
            case TransactionType::RECEIPT:
            case TransactionType::RETURN:
                $errors = array_merge($errors, $this->validateLocation($dto->toLocationId, 'toLocationId'));
                break;
            case TransactionType::ISSUE:
            case TransactionType::ADJUSTMENT:
            case TransactionType::CYCLE_COUNT:
            case TransactionType::ALLOCATE:
            case TransactionType::RESERVE:
            case TransactionType::BACKORDER:
                $errors = array_merge($errors, $this->validateLocation($dto->fromLocationId, 'fromLocationId'));
                break;
        }

        return $errors;
    }

    private function validateLocation(?int $locationId, string $locationKey): array
    {
        if ($locationId === null) {
            return ["Missing {$locationKey} for transaction"];
        }

        $location = Location::find($locationId);
        if (! $location) {
            return ["Invalid {$locationKey}: {$locationId}"];
        }

        return [];
    }

    private function validateInventoryLevels(InventoryTransactionDTO $dto): array
    {
        $errors = [];

        switch ($dto->transaction_type) {
            case TransactionType::TRANSFER:
            case TransactionType::ISSUE:
            case TransactionType::ALLOCATE:
            case TransactionType::RESERVE:
                $errors = array_merge($errors, $this->checkSufficientInventory($dto->inventoryableType, $dto->inventoryableId, $dto->fromLocationId, $dto->quantity));
                break;
            case TransactionType::ADJUSTMENT:
                if ($dto->quantity < 0) {
                    $errors = array_merge($errors, $this->checkSufficientInventory($dto->inventoryableType, $dto->inventoryableId, $dto->fromLocationId, abs($dto->quantity)));
                }
                break;
            case TransactionType::CYCLE_COUNT:
                $currentQuantity = $this->getCurrentInventoryQuantity($dto->inventoryableType, $dto->inventoryableId, $dto->fromLocationId);
                if ($dto->quantity < $currentQuantity) {
                    $errors = array_merge($errors, $this->checkSufficientInventory($dto->inventoryableType, $dto->inventoryableId, $dto->fromLocationId, $currentQuantity - $dto->quantity));
                }
                break;
            case TransactionType::BACKORDER:
            case TransactionType::RECEIPT:
            case TransactionType::RETURN:
                // No need to check inventory levels for these transaction types
                break;
        }

        return $errors;
    }

    private function checkSufficientInventory(string $inventoryableType, int $inventoryableId, int $locationId, int $requiredQuantity): array
    {
        $currentQuantity = $this->getCurrentInventoryQuantity($inventoryableType, $inventoryableId, $locationId);

        if ($currentQuantity < $requiredQuantity) {
            return ["Insufficient inventory. Required: {$requiredQuantity}, Available: {$currentQuantity}"];
        }

        return [];
    }

    private function getCurrentInventoryQuantity(string $inventoryableType, int $inventoryableId, int $locationId): int
    {
        $inventory = Inventory::where('inventoryable_type', $inventoryableType)
            ->where('inventoryable_id', $inventoryableId)
            ->where('location_id', $locationId)
            ->first();

        return $inventory ? $inventory->quantity_onhand : 0;
    }
}
