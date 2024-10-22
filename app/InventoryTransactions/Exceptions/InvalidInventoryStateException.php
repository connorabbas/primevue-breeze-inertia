<?php

namespace App\InventoryTransactions\Exceptions;

use Exception;

class InvalidInventoryStateException extends Exception
{
    public function __construct(string $message, ?Exception $previous = null)
    {
        $message = "Invalid transaction action: {$message}";
        parent::__construct($message, $previous);
    }
}
