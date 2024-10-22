<?php

namespace App\Models;

use App\DTOs\AddressDTO;
use App\DTOs\SupplierAddressesDTO;
use App\DTOs\IdentifierDTO;
use App\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'account_number',
        'payment_terms',
        'lead_time_days',
        'free_shipping_threshold_usd',
        'contact',
        'addresses',
        'identifiers',
    ];

    protected $casts = [
        'lead_time_days' => 'integer',
        'free_shipping_threshold_usd' => 'decimal:2',
        'contact' => 'json',
        'addresses' => SupplierAddressesDTO::class,
        'identifiers' => IdentifierDTO::class,
    ];

    protected $appends = ['free_shipping'];

    protected $with = ['parts', 'locations'];

    public function getFreeShippingAttribute(): bool
    {
        return $this->free_shipping_threshold_usd !== null && $this->free_shipping_threshold_usd >= 0;
    }

    public function setFreeShippingThresholdUsdAttribute($value)
    {
        $this->attributes['free_shipping_threshold_usd'] = $value !== null ? max(0, $value) : null;
    }

    public function parts(): HasMany
    {
        return $this->hasMany(Part::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    // Helper functions for addresses

    /**
     * @return array
     */
    public function getBillToAddresses(): array
    {
        return $this->addresses->billTo?->toArray() ?? [];
    }

    /**
     * @return array
     */
    public function getShipFromAddresses(): array
    {
        return $this->addresses->shipFrom?->toArray() ?? [];
    }

    /**
     * @return array
     */
    public function getShipToAddresses(): array
    {
        return $this->addresses->shipTo?->toArray() ?? [];
    }

    /**
     * @return array
     */
    public function getReturnToAddresses(): array
    {
        return $this->addresses->returnTo?->toArray() ?? [];
    }

    public function getBillToAddress(int $index = 0): ?array
    {
        $addresses = $this->getBillToAddresses();
        return $addresses[$index] ?? null;
    }

    public function getShipFromAddress(int $index = 0): ?array
    {
        $addresses = $this->getShipFromAddresses();
        return $addresses[$index] ?? null;
    }

    public function getShipToAddress(int $index = 0): ?array
    {
        $addresses = $this->getShipToAddresses();
        return $addresses[$index] ?? null;
    }

    public function getReturnToAddress(int $index = 0): ?array
    {
        $addresses = $this->getReturnToAddresses();
        return $addresses[$index] ?? null;
    }
}
