<?php

namespace App\InventoryTransactions\Strategies;

use App\Models\InventoryTransaction;
use App\Models\Location;
use Illuminate\Support\Facades\Log;

abstract class BaseStrategy
{
    protected ?Location $warehouseLocation = null;

    protected ?Location $productionLocation = null;

    public function __construct()
    {
        $this->warehouseLocation = $this->getWarehouseLocation();
        $this->productionLocation = $this->getProductionLocation();
    }

    abstract public function execute(InventoryTransaction $transaction): void;

    protected function logTransactionExecution(InventoryTransaction $transaction, string $message): void
    {
        Log::info($message, [
            'transaction_id' => $transaction->id,
            'action' => $transaction->transaction_type,
            'product_id' => $transaction->product_id,
            'quantity' => $transaction->quantity,
        ]);
    }

    protected function getWarehouseLocation(): Location
    {
        return Location::where('name', 'Warehouse')->firstOrFail();
    }

    protected function getProductionLocation(): Location
    {
        return Location::where('name', 'Production')->firstOrFail();
    }
}
