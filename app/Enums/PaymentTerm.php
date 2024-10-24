<?php

namespace App\Enums;

enum PaymentTerm: string
{
        // Standard terms
    case IMMEDIATE = 'immediate';           // Due immediately upon receipt
    case NET_7 = 'net_7';                  // Due within 7 days
    case NET_14 = 'net_14';                // Due within 14 days
    case NET_30 = 'net_30';                // Due within 30 days
    case NET_45 = 'net_45';                // Due within 45 days
    case NET_60 = 'net_60';                // Due within 60 days
    case NET_90 = 'net_90';                // Due within 90 days

        // End of month terms
    case EOM = 'end_of_month';             // Due at the end of the month
    case EOM_15 = 'eom_plus_15';           // Due 15 days after the end of the month
    case EOM_30 = 'eom_plus_30';           // Due 30 days after the end of the month

        // Invoice date terms
    case INV_7 = 'invoice_7';              // Due 7 days after invoice date
    case INV_14 = 'invoice_14';            // Due 14 days after invoice date
    case INV_30 = 'invoice_30';            // Due 30 days after invoice date
    case INV_45 = 'invoice_45';            // Due 45 days after invoice date
    case INV_60 = 'invoice_60';            // Due 60 days after invoice date

        // Shipping date terms
    case SHIP_7 = 'ship_7';                // Due 7 days after shipping date
    case SHIP_14 = 'ship_14';              // Due 14 days after shipping date
    case SHIP_30 = 'ship_30';              // Due 30 days after shipping date
    case SHIP_45 = 'ship_45';              // Due 45 days after shipping date

        // Combination terms (Invoice + Shipping)
    case INV_30_SHIP_70 = 'inv_30_ship_70';  // 30% due after invoice, 70% after shipping
    case INV_50_SHIP_50 = 'inv_50_ship_50';  // 50% due after invoice, 50% after shipping
    case INV_70_SHIP_30 = 'inv_70_ship_30';  // 70% due after invoice, 30% after shipping

    case CREDIT_CARD = 'credit_card';


    public function getDescription(): string
    {
        return match ($this) {
            self::IMMEDIATE => 'Due immediately upon receipt',
            self::NET_7 => 'Net 7 days',
            self::NET_14 => 'Net 14 days',
            self::NET_30 => 'Net 30 days',
            self::NET_45 => 'Net 45 days',
            self::NET_60 => 'Net 60 days',
            self::NET_90 => 'Net 90 days',
            self::EOM => 'End of month',
            self::EOM_15 => '15 days after end of month',
            self::EOM_30 => '30 days after end of month',
            self::INV_7 => '7 days after invoice date',
            self::INV_14 => '14 days after invoice date',
            self::INV_30 => '30 days after invoice date',
            self::INV_45 => '45 days after invoice date',
            self::INV_60 => '60 days after invoice date',
            self::SHIP_7 => '7 days after shipping date',
            self::SHIP_14 => '14 days after shipping date',
            self::SHIP_30 => '30 days after shipping date',
            self::SHIP_45 => '45 days after shipping date',
            self::INV_30_SHIP_70 => '30% after invoice date, 70% after shipping date',
            self::INV_50_SHIP_50 => '50% after invoice date, 50% after shipping date',
            self::INV_70_SHIP_30 => '70% after invoice date, 30% after shipping date',
            self::CREDIT_CARD => 'Credit card'
        };
    }

    public function getInvoicePercentage(): ?int
    {
        return match ($this) {
            self::INV_30_SHIP_70 => 30,
            self::INV_50_SHIP_50 => 50,
            self::INV_70_SHIP_30 => 70,
            default => null
        };
    }

    public function getShippingPercentage(): ?int
    {
        return match ($this) {
            self::INV_30_SHIP_70 => 70,
            self::INV_50_SHIP_50 => 50,
            self::INV_70_SHIP_30 => 30,
            default => null
        };
    }
}
