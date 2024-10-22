<?php

namespace App\Enums;

enum InventoryQuantityType: string
{
    case ONHAND = 'quantity_onhand';
    case ALLOCATED = 'quantity_allocated';
    case BACKORDERED = 'quantity_backordered';
    case RESERVED = 'quantity_reserved';
    case INTRANSIT = 'quantity_intransit';

    /**
     * Get the display name for the quantity type.
     */
    public function getDisplayName(): string
    {
        return match ($this) {
            self::ONHAND => 'On Hand',
            self::ALLOCATED => 'Allocated',
            self::BACKORDERED => 'Backordered',
            self::RESERVED => 'Reserved',
            self::INTRANSIT => 'In Transit',
        };
    }

    /**
     * Get all valid quantity types.
     */
    public static function getValidTypes(): array
    {
        return array_column(self::cases(), 'value');
    }
}
