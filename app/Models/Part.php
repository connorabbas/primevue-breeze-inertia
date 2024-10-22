<?php

namespace App\Models;

use App\Concerns\HasDimensions;
use App\DTOs\ReplenishmentDataDTO;
use App\DTOs\IdentifierDTO;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\LaravelData\Attributes\DataCollectionOf;

class Part extends Model
{
    use HasDimensions, HasFactory;

    protected $fillable = [
        'part_number',
        'quantity',
        'uom',
        'description',
        'identifiers',
        'regulatory_information',
        'replenishment_data',
        'manufacturer_id',
        'supplier_id',
        'lead_time_days',
    ];

    protected $casts = [
        'identifiers' => IdentifierDTO::class,
        'regulatory_information' => IdentifierDTO::class,
        'quantity' => 'integer',
        'replenishment_data' => ReplenishmentDataDTO::class,
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'bills_of_material')
            ->withPivot('quantity_required')
            ->withTimestamps();
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function getUnitCostAttribute()
    {
        return $this->replenishment_data->purchaseTerms[0]['cost_per_part'] ?? 0;
    }

    public function getIdentifierValue(string $type): ?string
    {
        return $this->identifiers->identifiers->firstWhere('type', $type)?->value;
    }

    public function getRegulatoryInformationValue(string $type): ?string
    {
        return $this->regulatory_information->identifiers->firstWhere('type', $type)?->value;
    }
}
