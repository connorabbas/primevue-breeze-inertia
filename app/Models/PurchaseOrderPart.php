<?php

namespace App\Models;

use App\Enums\PurchaseOrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderPart extends Model
{
    use HasFactory;
    protected $table = 'purchase_order_parts';
    protected $fillable = [
        'purchase_order_id',
        'part_id',
        'quantity_ordered',
        'unit_cost',
        'total_cost',
        'quantity_invoiced',
        'quantity_received',
        'notes',
    ];

    protected $casts = [
        'quantity_ordered' => 'integer',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'quantity_invoiced' => 'integer',
        'quantity_received' => 'integer',
    ];

    protected static function booted()
    {
        static::saving(function ($purchaseOrderPart) {
            $purchaseOrderPart->calculateTotalCost();
        });

        static::saved(function ($purchaseOrderPart) {
            $purchaseOrderPart->purchaseOrder->calculateTotals();
            $purchaseOrderPart->purchaseOrder->save();
        });
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class);
    }

    public function calculateTotalCost(): void
    {
        $this->total_cost = $this->quantity_ordered * $this->unit_cost;
    }

    public function getRemainingQuantityAttribute(): int
    {
        return $this->quantity_ordered - $this->quantity_received;
    }

    public function getStatusAttribute(): PurchaseOrderStatus
    {
        if ($this->quantity_received === 0) {
            return PurchaseOrderStatus::SUBMITTED;
        } elseif ($this->quantity_received < $this->quantity_ordered) {
            return PurchaseOrderStatus::PARTIALLY_RECEIVED;
        } else {
            return PurchaseOrderStatus::FULLY_RECEIVED;
        }
    }

    public function scopeSubmitted($query)
    {
        return $query->where('quantity_received', 0);
    }

    public function scopeReceived($query)
    {
        return $query->where('quantity_received', '>', 0);
    }

    public function scopeFullyReceived($query)
    {
        return $query->whereRaw('quantity_received = quantity_ordered');
    }

    public function scopePartiallyReceived($query)
    {
        return $query->whereRaw('quantity_received > 0 AND quantity_received < quantity_ordered');
    }
}
