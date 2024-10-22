<?php

namespace App\Enums\Replenishment;

enum RecommendedAction: string
{
    case NONE = 'None';
    case REORDER = 'Reorder';
    case RESTOCK = 'Restock';
    case HOLD = 'Hold';
}
