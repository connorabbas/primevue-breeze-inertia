<?php

namespace App\Enums;

use App\Concerns\HasEnumDescriptions;

enum PurchaseOrderStatus: string
{
    use HasEnumDescriptions;

    case DRAFT = 'draft';
    case SUBMITTED = 'submitted';
    case APPROVED = 'approved';
    case PARTIALLY_RECEIVED = 'partially_received';
    case FULLY_RECEIVED = 'fully_received';
    case CLOSED = 'closed';
    case CANCELLED = 'cancelled';

    public static function descriptions(): array
    {
        return [
            self::DRAFT => 'Draft',
            self::SUBMITTED => 'Submitted',
            self::APPROVED => 'Approved',
            self::PARTIALLY_RECEIVED => 'Partially Received',
            self::FULLY_RECEIVED => 'Fully Received',
            self::CLOSED => 'Closed',
            self::CANCELLED => 'Cancelled',
        ];
    }
}
