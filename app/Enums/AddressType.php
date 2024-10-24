<?php

namespace App\Enums;

enum AddressType: string
{
    case BILL_TO = 'bill_to';
    case SHIP_FROM = 'ship_from';
    case SHIP_TO = 'ship_to';
    case RETURN_TO = 'return_to';
    case MISC = 'misc';
    public function isRequired(): bool
    {
        return match ($this) {
            self::BILL_TO, self::SHIP_FROM, self::SHIP_TO => true,
            self::RETURN_TO, self::MISC => false,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::BILL_TO => 'Billing Address',
            self::SHIP_FROM => 'Ship From Address',
            self::SHIP_TO => 'Shipping Address',
            self::RETURN_TO => 'Return Address',
            self::MISC => 'Miscellaneous Address',
        };
    }
}
