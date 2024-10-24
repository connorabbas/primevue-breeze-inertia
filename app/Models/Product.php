<?php

namespace App\Models;

use App\Concerns\HasDimensions;
use App\DTOs\IdentifierDTO;
use App\DTOs\ReplenishmentDataDTO;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasDimensions;
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'identifiers',
        'replenishment_data',
        'weight_oz'
    ];

    protected $casts = [
        'identifiers' => IdentifierDTO::class,
        'replenishment_data' => ReplenishmentDataDTO::class,
        'weight_oz' => 'decimal'
    ];

    public function parts(): BelongsToMany
    {
        return $this->belongsToMany(Part::class, 'bills_of_material')
            ->withPivot('quantity_required')
            ->withTimestamps();
    }

    public function billOfMaterials(): HasMany
    {
        return $this->hasMany(BillOfMaterial::class);
    }

    public function gtin(): HasOne
    {
        return $this->hasOne(Gtin::class);
    }

    public function getIdentifierValue(string $type): ?string
    {
        return $this->identifiers->identifiers->firstWhere('type', $type)?->value;
    }

    public function getDisplayName(): string
    {
        return $this->sku() ?? $this->name ?? '';
    }

    public function scopeByIdentifierKey($query, string $identifierKey, string $value): Builder
    {
        return $query->where("identifiers->" . str($identifierKey)->snake(), $value);
    }
}
