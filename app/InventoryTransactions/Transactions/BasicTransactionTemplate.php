<?php

namespace App\InventoryTransactions\Transactions;

use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\DB;

abstract class BasicTransactionTemplate
{
    final public function executeTransaction(array $data): InventoryTransaction
    {
        return DB::transaction(function () use ($data) {
            $this->validateData($data);
            $preparedData = $this->prepareTransaction($data);
            $transaction = $this->createTransaction($preparedData);
            $this->postTransactionTypes($transaction);

            return $transaction;
        });
    }

    abstract protected function validateData(array $data): void;

    abstract protected function prepareTransaction(array $data): array;

    abstract protected function createTransaction(array $data): InventoryTransaction;

    abstract protected function postTransactionTypes(InventoryTransaction $transaction): void;
}
