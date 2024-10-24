<?php

namespace App\Models;

use App\Data\AddressData;
use App\Enums\PurchaseOrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'number',
        'supplier_id',
        'location_id',
        'status',
        'total_cost',
        'user_id',
        'opened_at',
        'closed_at',
        'addresses',
        'special_instructions',
        'tax_rate',
        'additional_costs',
    ];

    protected $casts = [
        'status' => PurchaseOrderStatus::class,
        'total_cost' => 'decimal:2',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'addresses' => 'array', // Store as plain JSON since we need to snapshot the addresses
        'tax_rate' => 'decimal:2',
        'additional_costs' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($purchaseOrder) {
            if (!$purchaseOrder->number) {
                $purchaseOrder->number = static::getPurchaseOrderNumber();
            }
        });

        static::saving(function ($purchaseOrder) {
            $purchaseOrder->calculateTotals();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function parts(): HasMany
    {
        return $this->hasMany(PurchaseOrderPart::class);
    }

    public function setStatus(PurchaseOrderStatus $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function calculateTotals(): void
    {
        $subtotal = $this->parts->sum('total_cost');
        $tax = $subtotal * ($this->tax_rate / 100);
        $this->total_cost = $subtotal + $tax + $this->additional_costs;
    }

    public static function getPurchaseOrderNumber(): int
    {
        $lastPO = static::orderBy('number', 'desc')->first();

        if ($lastPO) {
            $newNumber = $lastPO->number + 1;
        } else {
            $newNumber = 1;
        }

        return $newNumber;
    }

    public function isEditable(): bool
    {
        return in_array($this->status, [
            PurchaseOrderStatus::DRAFT,
            PurchaseOrderStatus::SUBMITTED,
        ]);
    }
}
