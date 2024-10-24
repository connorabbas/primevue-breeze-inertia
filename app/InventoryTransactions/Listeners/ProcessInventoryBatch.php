<?php

namespace App\InventoryTransactions\Listeners;

use App\InventoryTransactions\Events\InventoryBatchCreated;
use App\InventoryTransactions\Services\InventoryService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ProcessInventoryBatch implements ShouldQueue
{
    public function __construct(
        private InventoryService $inventoryService
    ) {
    }

    public function handle(InventoryBatchCreated $event): void
    {
        try {
            $this->inventoryService->processBatch($event->batch);
        } catch (\Exception $e) {
            Log::error('Error processing inventory batch', [
                'batch_id' => $event->batch->id,
                'error' => $e->getMessage(),
            ]);
            // The InventoryBatchFailed event is now dispatched in the InventoryService
        }
    }
}
