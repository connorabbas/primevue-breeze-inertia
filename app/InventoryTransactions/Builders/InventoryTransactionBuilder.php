<?php

namespace App\InventoryTransactions\Builders;

use App\InventoryTransactions\DTOs\InventoryTransactionDTO;
use App\InventoryTransactions\Exceptions\InvalidTransactionData;
use App\InventoryTransactions\Validators\InventoryTransactionValidator;
use App\Models\InventoryTransaction;
use App\Models\InventoryTransactionBatch;
use Illuminate\Support\Str;

class InventoryTransactionBuilder
{
    private InventoryTransactionBatch $batch;

    private array $transactions = [];

    private InventoryTransactionValidator $validator;

    public function __construct(InventoryTransactionValidator $validator)
    {
        $this->batch = new InventoryTransactionBatch();
        $this->batch->status = InventoryTransactionBatch::STATUS_PENDING;
        $this->batch->ulid = (string) Str::ulid();
        $this->validator = $validator;
    }

    public function addTransaction(InventoryTransactionDTO $dto): self
    {
        try {
            $this->validator->validate($dto);

            $this->transactions[] = [
                'ulid' => (string) Str::ulid(),
                'transaction_type' => $dto->transaction_type,
                'quantity' => $dto->quantity,
                'inventoryable_type' => $dto->inventoryableType,
                'inventoryable_id' => $dto->inventoryableId,
                'from_location_id' => $dto->fromLocationId,
                'to_location_id' => $dto->toLocationId,
                'reason' => $dto->reason,
                'user_id' => $dto->userId,
            ];
        } catch (InvalidTransactionData $e) {
            // Log the error or handle it as needed
            // For now, we'll just re-throw the exception
            throw $e;
        }

        return $this;
    }

    public function setBatchDescription(string $description): self
    {
        $this->batch->description = $description;

        return $this;
    }

    public function setUser(int $userId): self
    {
        $this->batch->user_id = $userId;

        return $this;
    }

    public function setReference(string $type, int $id): self
    {
        $this->batch->reference_type = $type;
        $this->batch->reference_id = $id;

        return $this;
    }

    public function build(): InventoryTransactionBatch
    {
        if (empty($this->transactions)) {
            throw new InvalidTransactionData('Cannot create an empty batch');
        }

        $this->batch->save();

        $transactionsToInsert = array_map(function ($transaction) {
            $transaction['batch_id'] = $this->batch->id;

            return $transaction;
        }, $this->transactions);

        InventoryTransaction::insert($transactionsToInsert);

        return $this->batch->load('transactions');
    }
}
