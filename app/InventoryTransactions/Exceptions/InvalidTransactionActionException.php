<?php

namespace App\InventoryTransactions\Exceptions;

use App\Models\InventoryTransaction;
use Exception;

class InvalidTransactionTypeException extends Exception
{
    private InventoryTransaction $transaction;

    public function __construct(InventoryTransaction $transaction, int $code = 0, ?Exception $previous = null)
    {
        $this->transaction = $transaction;
        $message = "Invalid transaction action: {$transaction->transaction_type->value}";
        parent::__construct($message, $code, $previous);
    }

    public function getTransaction(): InventoryTransaction
    {
        return $this->transaction;
    }

    public function getLogMessage(): string
    {
        return json_encode($this->transaction->toArray());
    }
}
