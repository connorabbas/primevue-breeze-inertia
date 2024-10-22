<?php

namespace App\Enums;

enum BarcodeIDTypeEnum: string
{
    case FNSKU = 'C128';
    case GTIN = 'UPCA';
    case UPC = 'UPCA';
    case EAN = 'UPCA';
}
