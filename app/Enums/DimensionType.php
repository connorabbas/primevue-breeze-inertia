<?php

namespace App\Enums;

enum DimensionType: string
{
    case BOX = 'box';
    case PALLET = 'pallet';
    case INDIVIDUAL_UNIT = 'individual_unit';
    case PRODUCT = 'product';
    case PACKAGING = 'packaging';
}
