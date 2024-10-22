<?php

namespace App\InventoryTransactions\Listeners;

use App\InventoryTransactions\Events\InventoryBatchFailed;
use Illuminate\Support\Facades\Log;

class HandleInventoryBatchFailure
{
    public function handle(InventoryBatchFailed $event): void
    {
        Log::error('Inventory batch processing failed', [
            'batch_id' => $event->batch->id,
            'error' => $event->error,
        ]);
        // Implement any failure handling logic here, such as notifications or retries
    }
}
