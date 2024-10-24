<?php

namespace App\Models;

use App\Data\AddressData;
use App\Enums\AddressType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'address_data'
    ];

    protected $casts = [
        'address_data' => AddressData::class,
    ];

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class, 'supplier_address')
            ->withPivot('address_type')
            ->withTimestamps();
    }

    /**
     * Get the formatted address data
     */
    public function getFormattedAddress(): AddressData
    {
        return $this->address_data;
    }

    /**
     * Create a snapshot of this address for a purchase order
     */
    public function createSnapshot(string $type): array
    {
        return [
            'address_type' => $type,
            'address_data' => $this->address_data->toArray(),
        ];
    }
}
