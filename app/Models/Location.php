<?php

namespace App\Models;

use App\DTOs\LocationAddressesDTO;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

class Location extends Model
{
    use HasFactory;

    const TYPE_WAREHOUSE = 'warehouse';
    const TYPE_SUPPLIER = 'supplier';
    const TYPE_RACK = 'rack';
    const TYPE_BIN = 'bin';
    const TYPE_VIRTUAL = 'virtual';

    const VIRTUAL_TYPE_BILL_TO = 'bill_to';
    const VIRTUAL_TYPE_SHIP_TO = 'ship_to';
    const VIRTUAL_TYPE_WORK_ORDER = 'work_order';

    protected $fillable = [
        'name',
        'virtual_type',
        'addresses',
        'type',
        'parent_id',
        'supplier_id',
    ];

    protected $casts = [
        'addresses' => LocationAddressesDTO::class,
    ];

    protected static $hierarchyMap = [
        self::TYPE_WAREHOUSE => null,
        self::TYPE_SUPPLIER => null,
        self::TYPE_RACK => self::TYPE_WAREHOUSE,
        self::TYPE_BIN => self::TYPE_RACK,
        self::TYPE_VIRTUAL => [self::TYPE_WAREHOUSE, self::TYPE_SUPPLIER],
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($location) {
            $location->validateHierarchy();
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    protected function validateHierarchy()
    {
        $allowedParentType = self::$hierarchyMap[$this->type] ?? null;

        if ($allowedParentType === null && $this->parent_id !== null) {
            throw ValidationException::withMessages([
                'parent_id' => ucfirst($this->type) . ' cannot have a parent location.',
            ]);
        }

        if ($allowedParentType !== null && $this->parent_id === null) {
            $errorMessage = $this->getParentTypeErrorMessage($allowedParentType);
            throw ValidationException::withMessages([
                'parent_id' => $errorMessage,
            ]);
        }

        if ($allowedParentType !== null && $this->parent) {
            $isValidParent = is_array($allowedParentType)
                ? in_array($this->parent->type, $allowedParentType)
                : $this->parent->type === $allowedParentType;

            if (!$isValidParent) {
                $errorMessage = $this->getParentTypeErrorMessage($allowedParentType);
                throw ValidationException::withMessages([
                    'parent_id' => $errorMessage,
                ]);
            }
        }
    }

    protected function getParentTypeErrorMessage($allowedParentType): string
    {
        if (is_array($allowedParentType)) {
            $parentTypes = array_map('ucfirst', $allowedParentType);
            return ucfirst($this->type) . ' must have a ' . implode(' or ', $parentTypes) . ' as parent.';
        }
        return ucfirst($this->type) . ' must have a ' . ucfirst($allowedParentType) . ' as parent.';
    }

    public static function getValidTypes(): array
    {
        return [
            self::TYPE_WAREHOUSE,
            self::TYPE_RACK,
            self::TYPE_BIN,
            self::TYPE_VIRTUAL,
            self::TYPE_SUPPLIER,
        ];
    }

    public static function getValidVirtualTypes(): array
    {
        return [
            self::VIRTUAL_TYPE_BILL_TO,
            self::VIRTUAL_TYPE_SHIP_TO,
            self::VIRTUAL_TYPE_WORK_ORDER,
        ];
    }

    public function inventory(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function billToPurchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'bill_to_location_id');
    }

    public function supplierPurchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'supplier_location_id');
    }

    public function shipFromPurchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'ship_from_location_id');
    }
}
