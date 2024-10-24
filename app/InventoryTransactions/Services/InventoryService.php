<?php

namespace App\InventoryTransactions\Services;

use App\InventoryTransactions\Builders\InventoryTransactionBuilder;
use App\InventoryTransactions\DTOs\InventoryTransactionDTO;
use App\InventoryTransactions\Events\InventoryBatchCreated;
use App\InventoryTransactions\Events\InventoryBatchFailed;
use App\InventoryTransactions\Events\InventoryBatchProcessed;
use App\InventoryTransactions\Exceptions\BatchProcessingException;
use App\InventoryTransactions\Exceptions\InsufficientInventoryException;
use App\InventoryTransactions\Exceptions\InvalidLocationException;
use App\InventoryTransactions\Exceptions\InvalidTransactionData;
use App\InventoryTransactions\Validators\InventoryTransactionValidator;
use App\Models\InventoryTransactionBatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryService
{
    public function __construct(
        private InventoryTransactionBuilder $builder,
        private InventoryUpdateService $updateService,
        private InventoryTransactionValidator $validator
    ) {
    }

    public function createBatch(array $transactionData, int $userId): InventoryTransactionBatch
    {
        Log::info('Creating inventory transaction batch', ['user_id' => $userId, 'transaction_count' => count($transactionData)]);

        try {
            $this->validateTransactionData($transactionData);

            $batch = $this->builder->setUser($userId);

            foreach ($transactionData as $index => $transaction) {
                $dto = InventoryTransactionDTO::from($transaction);
                $batch->addTransaction($dto);
            }

            $batch = $batch->build();

            InventoryBatchCreated::dispatch($batch);

            return $batch;
        } catch (\Exception $e) {
            Log::error('Failed to create inventory transaction batch', ['error' => $e->getMessage(), 'exception' => get_class($e), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    public function processBatch(InventoryTransactionBatch $batch): void
    {
        Log::info('Processing inventory transaction batch', ['batch_id' => $batch->id, 'transaction_count' => $batch->transactions->count()]);

        DB::beginTransaction();
        try {
            $processedTransactions = [];

            foreach ($batch->transactions as $transaction) {
                Log::info('Processing transaction', ['transaction_id' => $transaction->id, 'type' => $transaction->transaction_type]);

                $result = $this->updateService->processTransaction($transaction);

                if ($result['success']) {
                    $processedTransactions[] = $transaction->id;
                    Log::info('Transaction processed successfully', ['transaction_id' => $transaction->id]);
                } else {
                    throw new \Exception($result['error']);
                }
            }

            $batch->update(['status' => InventoryTransactionBatch::STATUS_COMPLETED]);
            DB::commit();

            InventoryBatchProcessed::dispatch($batch);
        } catch (InsufficientInventoryException $e) {
            $this->handleBatchProcessingFailure($batch, $e, $processedTransactions);
            throw new BatchProcessingException("Failed to process batch {$batch->id}: Insufficient inventory. " . $e->getMessage(), 0, $e);
        } catch (InvalidLocationException $e) {
            $this->handleBatchProcessingFailure($batch, $e, $processedTransactions);
            throw new BatchProcessingException("Failed to process batch {$batch->id}: Invalid location. " . $e->getMessage(), 0, $e);
        } catch (\Exception $e) {
            $this->handleBatchProcessingFailure($batch, $e, $processedTransactions);
            throw new BatchProcessingException("Failed to process batch {$batch->id}: " . $e->getMessage(), 0, $e);
        }
    }

    private function validateTransactionData(array $transactionData): void
    {
        foreach ($transactionData as $index => $transaction) {
            try {
                $this->validator->validate($transaction);
            } catch (InvalidTransactionData $e) {
                throw new \InvalidArgumentException("Invalid transaction data at index {$index}: " . $e->getMessage());
            }
        }
    }

    private function handleBatchProcessingFailure(InventoryTransactionBatch $batch, \Exception $e, array $processedTransactions): void
    {
        DB::rollBack();
        $batch->update(['status' => InventoryTransactionBatch::STATUS_FAILED]);

        Log::error('Failed to process inventory transaction batch', [
            'batch_id' => $batch->id,
            'processed_transactions' => $processedTransactions,
            'error' => $e->getMessage(),
            'exception' => get_class($e),
            'trace' => $e->getTraceAsString(),
        ]);

        InventoryBatchFailed::dispatch($batch, $e);
    }
}
