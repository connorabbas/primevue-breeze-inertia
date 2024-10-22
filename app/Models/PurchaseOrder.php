<?php

namespace App\Models;

use App\DTOs\AddressDTO;
use App\Enums\PurchaseOrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'number',
        'supplier_id',
        'location_id',
        'status',
        'total_cost',
        'user_id',
        'opened_at',
        'closed_at',
        'bill_to_address_index',
        'ship_from_address_index',
        'ship_to_address_index',
    ];

    protected $casts = [
        'status' => PurchaseOrderStatus::class,
        'total_cost' => 'decimal:2',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'bill_to_address_index' => 'integer',
        'ship_from_address_index' => 'integer',
        'ship_to_address_index' => 'integer',
    ];

    protected $appends = ['bill_to_address', 'ship_from_address', 'ship_to_address'];

    protected static function booted()
    {
        static::creating(function ($purchaseOrder) {
            if (!$purchaseOrder->number) {
                $purchaseOrder->number = static::getPurchaseOrderNumber();
            }
        });

        static::saving(function ($purchaseOrder) {
            $purchaseOrder->updateTotalCost();
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

    public function purchaseOrderParts(): HasMany
    {
        return $this->hasMany(PurchaseOrderPart::class);
    }

    public function getBillToAddressAttribute(): ?AddressDTO
    {
        return $this->supplier->addresses->billTo[$this->bill_to_address_index] ?? null;
    }

    public function getShipFromAddressAttribute(): ?AddressDTO
    {
        return $this->supplier->addresses->shipFrom[$this->ship_from_address_index] ?? null;
    }

    public function getShipToAddressAttribute(): ?AddressDTO
    {
        return $this->location->addresses->shipTo[$this->ship_to_address_index] ?? null;
    }

    public function setStatus(PurchaseOrderStatus $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function calculateTotalCost(): float
    {
        return $this->purchaseOrderParts->sum('total_cost');
    }

    public function updateTotalCost(): void
    {
        $this->total_cost = $this->calculateTotalCost();
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
