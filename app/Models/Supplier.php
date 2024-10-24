<?php

namespace App\Models;

use App\DTOs\IdentifierDTO;
use App\Enums\AddressType;
use App\Enums\PaymentTerm;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'identifiers',
    ];

    protected $casts = [
        'payment_terms' => PaymentTerm::class,
        'lead_time_days' => 'integer',
        'free_shipping_threshold_usd' => 'decimal:2',
        'contact' => 'json',
        'identifiers' => IdentifierDTO::class,
    ];

    protected $appends = ['free_shipping'];

    public function getFreeShippingAttribute(): bool
    {
        return $this->free_shipping_threshold_usd !== null &&
            $this->free_shipping_threshold_usd >= 0;
    }

    public function setFreeShippingThresholdUsdAttribute($value)
    {
        $this->attributes['free_shipping_threshold_usd'] =
            $value !== null ? max(0, $value) : null;
    }

    public function parts(): HasMany
    {
        return $this->hasMany(Part::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function addresses(): BelongsToMany
    {
        return $this->belongsToMany(Address::class, 'supplier_address')
            ->withPivot('address_type')
            ->withTimestamps()
            ->withCasts([
                'address_type' => AddressType::class
            ]);
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * Get all addresses of type billTo
     */
    public function getBillToAddresses(): array
    {
        return $this->addresses()
            ->wherePivot('address_type', AddressType::BILL_TO)
            ->get()
            ->map(fn($address) => $address->address_data->toArray())
            ->toArray();
    }

    /**
     * Get all addresses of type shipFrom
     */
    public function getShipFromAddresses(): array
    {
        return $this->addresses()
            ->wherePivot('address_type', AddressType::SHIP_FROM)
            ->get()
            ->map(fn($address) => $address->address_data->toArray())
            ->toArray();
    }

    /**
     * Get all addresses of type shipTo
     */
    public function getShipToAddresses(): array
    {
        return $this->addresses()
            ->wherePivot('address_type', AddressType::SHIP_TO)
            ->get()
            ->map(fn($address) => $address->address_data->toArray())
            ->toArray();
    }

    /**
     * Get all addresses of type returnTo
     */
    public function getReturnToAddresses(): array
    {
        return $this->addresses()
            ->wherePivot('address_type', AddressType::RETURN_TO)
            ->get()
            ->map(fn($address) => $address->address_data->toArray())
            ->toArray();
    }

    /**
     * Get a specific billTo address by index
     */
    public function getBillToAddress(int $index = 0): ?array
    {
        $address = $this->addresses()
            ->wherePivot('address_type', AddressType::BILL_TO)
            ->first();
        return $address ? $address->address_data->toArray() : null;
    }

    /**
     * Get a specific shipFrom address by index
     */
    public function getShipFromAddress(int $index = 0): ?array
    {
        $address = $this->addresses()
            ->wherePivot('address_type', AddressType::SHIP_FROM)
            ->first();
        return $address ? $address->address_data->toArray() : null;
    }

    /**
     * Get a specific shipTo address by index
     */
    public function getShipToAddress(int $index = 0): ?array
    {
        $address = $this->addresses()
            ->wherePivot('address_type', AddressType::SHIP_TO)
            ->first();
        return $address ? $address->address_data->toArray() : null;
    }

    /**
     * Get a specific returnTo address by index
     */
    public function getReturnToAddress(int $index = 0): ?array
    {
        $address = $this->addresses()
            ->wherePivot('address_type', AddressType::RETURN_TO)
            ->first();
        return $address ? $address->address_data->toArray() : null;
    }

    /**
     * Scope a query to only include active suppliers.
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope to load suppliers with their parts and addresses
     */
    public function scopeWithPartsAndAddresses($query)
    {
        return $query->with(['parts'])
            ->select(['id', 'name', 'account_number'])
            ->get()
            ->map(function ($supplier) {
                // Transform addresses into the expected format
                $addresses = [
                    'billTo' => $supplier->getBillToAddresses(),
                    'shipFrom' => $supplier->getShipFromAddresses(),
                    'shipTo' => $supplier->getShipToAddresses(),
                    'returnTo' => $supplier->getReturnToAddresses()
                ];
                return array_merge($supplier->toArray(), ['addresses' => $addresses]);
            });
    }
}
