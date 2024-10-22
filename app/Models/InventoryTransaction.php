<?php

namespace App\Models;

use App\InventoryTransactions\Enums\TransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'ulid',
        'batch_id',
        'inventoryable_type',
        'inventoryable_id',
        'quantity',
        'transaction_type',
        'from_location_id',
        'to_location_id',
        'reason',
        'user_id',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'transaction_type' => TransactionType::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['action'];

    public function addTransaction(InventoryTransaction $transaction): void
    {
        $transaction->batch_id = $this->id;
        $transaction->save();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->ulid = $model->ulid ?? (string) Str::ulid();
        });
    }

    public function isDeduction(): bool
    {
        return in_array($this->transaction_type, [
            TransactionType::ISSUE,
            TransactionType::TRANSFER,
            TransactionType::ALLOCATE,
            TransactionType::RESERVE,
        ]);
    }

    public function isAddition(): bool
    {
        return in_array($this->transaction_type, [
            TransactionType::RECEIPT,
            TransactionType::RETURN,
        ]);
    }

    public function isNeutral(): bool
    {
        return in_array($this->transaction_type, [
            TransactionType::ADJUSTMENT,
            TransactionType::CYCLE_COUNT,
            TransactionType::BACKORDER,
        ]);
    }

    public function getActionAttribute(): string
    {
        return $this->transaction_type->value;
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(InventoryTransactionBatch::class, 'batch_id');
    }

    public function inventoryable()
    {
        return $this->morphTo();
    }

    public function fromLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    public function toLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
