<?php

namespace App\InventoryTransactions\Enums;

enum TransactionType: string
{
    case ADJUSTMENT = 'adjustment';
    case TRANSFER = 'transfer';
    case RECEIPT = 'receipt';
    case ISSUE = 'issue';
    case RETURN = 'return';
    case CYCLE_COUNT = 'cycle_count';
    case ALLOCATE = 'allocate';
    case RESERVE = 'reserve';
    case BACKORDER = 'backorder';
}
