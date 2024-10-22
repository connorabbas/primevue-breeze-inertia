<?php

namespace App\InventoryTransactions\Exceptions;

use Exception;

class InvalidTransactionData extends Exception
{
    public function __construct($message = 'Invalid transaction data', $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
