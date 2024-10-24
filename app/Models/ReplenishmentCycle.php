<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ReplenishmentCycle extends Model
{
    use HasUuids;

    protected $fillable = [
        'title',
        'cycle_number',
        'status', // planning, in_progress, completed, cancelled
        'start_date',
        'target_completion_date',
        'actual_completion_date',
        'created_by_id',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'target_completion_date' => 'datetime',
        'actual_completion_date' => 'datetime'
    ];

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'cycle_id');
    }

    // Get all unique suppliers in this cycle
    public function suppliers()
    {
        return Supplier::whereHas('purchaseOrders', function ($query) {
            $query->where('cycle_id', $this->id);
        });
    }

    // Get total value of all POs in cycle
    public function getTotalValueAttribute(): float
    {
        return $this->purchaseOrders->sum('total_cost');
    }
}
