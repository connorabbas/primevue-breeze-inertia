<?php

namespace App\InventoryTransactions\Enums;

enum TransactionSourceType: string
{
    case PURCHASE_ORDER = 'purchase_order';
    case SALES_ORDER = 'sales_order';
    case INTERNAL_TRANSFER = 'internal_transfer';
    case ADJUSTMENT = 'adjustment';
    case PRODUCTION = 'production';
    case RETURN = 'return';
    case CYCLE_COUNT = 'cycle_count';
}
