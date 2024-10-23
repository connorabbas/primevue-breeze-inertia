<?php

return [
    'purchase_orders' => [
        /*
    |--------------------------------------------------------------------------
    | Default Tax Rate
    |--------------------------------------------------------------------------
    |
    | This value is the default tax rate that will be used when creating new
    | purchase orders. The value is in percentage (e.g., 8.25 for 8.25%).
    |
    */
        'default_tax_rate' => env('DEFAULT_TAX_RATE', 8.25),
    ],
];
