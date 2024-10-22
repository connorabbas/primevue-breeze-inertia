<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'inventoryable_id',
        'inventoryable_type',
        'quantity_onhand',
        'quantity_intransit',
        'quantity_backordered',
        'quantity_allocated',
        'quantity_reserved',
        'version',
    ];

    protected $casts = [
        'quantity_onhand' => 'integer',
        'quantity_intransit' => 'integer',
        'quantity_backordered' => 'integer',
        'quantity_allocated' => 'integer',
        'quantity_reserved' => 'integer',
        'version' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if ($model->isDirty('version')) {
                throw new \Exception('This inventory has been modified. Please retry the operation.');
            }
            $model->version++;
        });
    }

    public function inventoryable()
    {
        return $this->morphTo();
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the total available quantity.
     *
     * @return int
     */
    public function getAvailableQuantityAttribute()
    {
        return $this->quantity_onhand - $this->quantity_allocated - $this->quantity_reserved;
    }

    /**
     * Get the total quantity.
     *
     * @return int
     */
    public function getTotalQuantityAttribute()
    {
        return $this->quantity_onhand + $this->quantity_intransit;
    }
}
