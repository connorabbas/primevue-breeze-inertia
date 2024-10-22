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

class Product extends Model
{
    use HasDimensions, HasFactory;

    protected $fillable = [
        'name',
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

    public function getMasterSku(): ?string
    {
        return $this->getIdentifierValue('master_sku');
    }

    public function getIdentifierValue(string $type): ?string
    {
        return $this->identifiers->identifiers->firstWhere('type', $type)?->value;
    }

    public function getDisplayName(): string
    {
        return $this->getMasterSku() ?? $this->name ?? '';
    }
}
