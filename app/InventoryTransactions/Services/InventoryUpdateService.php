<?php

namespace App\InventoryTransactions\Services;

use App\InventoryTransactions\Enums\TransactionType;
use App\InventoryTransactions\Exceptions\InsufficientInventoryException;
use App\InventoryTransactions\Exceptions\InvalidLocationException;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryUpdateService
{
    public function processTransaction(InventoryTransaction $transaction): array
    {
        Log::info('Processing transaction', [
            'transaction_id' => $transaction->id,
            'type' => $transaction->transaction_type,
            'quantity' => $transaction->quantity,
            'from_location' => $transaction->from_location_id,
            'to_location' => $transaction->to_location_id,
            'inventoryable_type' => $transaction->inventoryable_type,
            'inventoryable_id' => $transaction->inventoryable_id,
        ]);

        try {
            DB::beginTransaction();

            switch ($transaction->transaction_type) {
                case TransactionType::RECEIPT:
                    $this->processReceipt($transaction);
                    break;
                case TransactionType::ISSUE:
                    $this->processIssue($transaction);
                    break;
                case TransactionType::TRANSFER:
                    $this->processTransfer($transaction);
                    break;
                case TransactionType::ADJUSTMENT:
                    $this->processAdjustment($transaction);
                    break;
                case TransactionType::RETURN:
                    $this->processReturn($transaction);
                    break;
                case TransactionType::CYCLE_COUNT:
                    $this->processCycleCount($transaction);
                    break;
                case TransactionType::ALLOCATE:
                    $this->processAllocate($transaction);
                    break;
                case TransactionType::RESERVE:
                    $this->processReserve($transaction);
                    break;
                case TransactionType::BACKORDER:
                    $this->processBackorder($transaction);
                    break;
                default:
                    throw new \InvalidArgumentException("Unsupported transaction type: {$transaction->transaction_type->value}");
            }

            DB::commit();
            Log::info('Transaction processed successfully', ['transaction_id' => $transaction->id]);

            return ['success' => true];
        } catch (InsufficientInventoryException|InvalidLocationException $e) {
            DB::rollBack();
            Log::error($e->getMessage(), [
                'transaction_id' => $transaction->id,
                'exception' => get_class($e),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Unexpected error processing transaction', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => 'An unexpected error occurred'];
        }
    }

    private function processReceipt(InventoryTransaction $transaction): void
    {
        $toInventory = $this->getOrCreateInventory($transaction, $transaction->to_location_id);
        $toInventory->quantity_onhand += $transaction->quantity;
        $toInventory->save();
    }

    private function processIssue(InventoryTransaction $transaction): void
    {
        $fromInventory = $this->getOrCreateInventory($transaction, $transaction->from_location_id);
        $this->checkSufficientInventory($fromInventory, $transaction->quantity);
        $fromInventory->quantity_onhand -= $transaction->quantity;
        $fromInventory->save();
    }

    private function processTransfer(InventoryTransaction $transaction): void
    {
        $fromInventory = $this->getOrCreateInventory($transaction, $transaction->from_location_id);
        $toInventory = $this->getOrCreateInventory($transaction, $transaction->to_location_id);
        $this->checkSufficientInventory($fromInventory, $transaction->quantity);
        $fromInventory->quantity_onhand -= $transaction->quantity;
        $toInventory->quantity_onhand += $transaction->quantity;
        $fromInventory->save();
        $toInventory->save();
    }

    private function processAdjustment(InventoryTransaction $transaction): void
    {
        $inventory = $this->getOrCreateInventory($transaction, $transaction->from_location_id);
        $inventory->quantity_onhand += $transaction->quantity; // Can be positive or negative
        $inventory->save();
    }

    private function processReturn(InventoryTransaction $transaction): void
    {
        $toInventory = $this->getOrCreateInventory($transaction, $transaction->to_location_id);
        $toInventory->quantity_onhand += $transaction->quantity;
        $toInventory->save();
    }

    private function processCycleCount(InventoryTransaction $transaction): void
    {
        $inventory = $this->getOrCreateInventory($transaction, $transaction->from_location_id);
        $inventory->quantity_onhand = $transaction->quantity; // Set to the counted quantity
        $inventory->save();
    }

    private function processAllocate(InventoryTransaction $transaction): void
    {
        $inventory = $this->getOrCreateInventory($transaction, $transaction->from_location_id);
        $this->checkSufficientInventory($inventory, $transaction->quantity);
        $inventory->quantity_allocated += $transaction->quantity;
        $inventory->save();
    }

    private function processReserve(InventoryTransaction $transaction): void
    {
        $inventory = $this->getOrCreateInventory($transaction, $transaction->from_location_id);
        $this->checkSufficientInventory($inventory, $transaction->quantity);
        $inventory->quantity_reserved += $transaction->quantity;
        $inventory->save();
    }

    private function processBackorder(InventoryTransaction $transaction): void
    {
        $inventory = $this->getOrCreateInventory($transaction, $transaction->from_location_id);
        $inventory->quantity_backordered += $transaction->quantity;
        $inventory->save();
    }

    private function getOrCreateInventory(InventoryTransaction $transaction, ?int $locationId): Inventory
    {
        if (! $locationId) {
            throw new InvalidLocationException('Unable to determine location for inventory');
        }

        return Inventory::firstOrCreate(
            [
                'inventoryable_type' => $transaction->inventoryable_type,
                'inventoryable_id' => $transaction->inventoryable_id,
                'location_id' => $locationId,
            ],
            [
                'quantity_onhand' => 0,
                'quantity_intransit' => 0,
                'quantity_backordered' => 0,
                'quantity_allocated' => 0,
                'quantity_reserved' => 0,
            ]
        );
    }

    private function checkSufficientInventory(Inventory $inventory, int $quantity): void
    {
        if ($inventory->quantity_onhand < $quantity) {
            throw new InsufficientInventoryException(
                "Insufficient inventory. Available: {$inventory->quantity_onhand}, Requested: {$quantity}"
            );
        }
    }
}
