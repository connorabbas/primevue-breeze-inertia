<?php

namespace App\Models;

use App\Enums\DimensionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Log;

class Dimension extends Model
{
    use HasFactory;

    protected $fillable = [
        'length',
        'width',
        'height',
        'unit',
        'type',
        'volume',
    ];

    protected $casts = [
        'type' => DimensionType::class,
    ];

    protected static function booted()
    {
        static::saving(function ($dimension) {
            $dimension->calculateVolume();
        });
    }

    public function calculateVolume(): void
    {
        try {
            $this->volume = $this->length * $this->width * $this->height;
        } catch (\Exception $e) {
            Log::error('Error calculating volume: ' . $e->getMessage());
        }
    }

    public function getFormattedDimensionsAttribute(): string
    {
        return "{$this->length} x {$this->width} x {$this->height} {$this->unit}";
    }

    public function scopeOfType($query, DimensionType $type)
    {
        return $query->where('type', $type->value);
    }

    public static function findByAttributesOrFail(array $attributes)
    {
        $query = static::query();

        foreach ($attributes as $key => $value) {
            $query->where($key, $value);
        }

        $result = $query->first();

        if (! $result) {
            throw (new ModelNotFoundException)->setModel(
                static::class,
                array_values($attributes)
            );
        }

        return $result;
    }

    public static function findByDimensionsOrFail(array $dimensions, ?string $unit = null)
    {
        if (count($dimensions) !== 3) {
            throw new \InvalidArgumentException('Dimensions array must contain exactly 3 values [L, W, H]');
        }

        [$length, $width, $height] = $dimensions;

        $query = static::query()
            ->where('length', $length)
            ->where('width', $width)
            ->where('height', $height);

        if ($unit !== null) {
            $query->where('unit', $unit);
        }

        $result = $query->first();

        if (! $result) {
            throw (new ModelNotFoundException)->setModel(
                static::class,
                [$length, $width, $height, $unit]
            );
        }

        return $result;
    }

    public function parts(): MorphToMany
    {
        return $this->morphedByMany(Part::class, 'dimensionable')->withPivot('dimensionable_type');
    }

    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'dimensionable')->withPivot('dimensionable_type');
    }

    public function morphedByMany($related, $name, $table = null, $foreignPivotKey = null, $relatedPivotKey = null, $parentKey = null, $relatedKey = null, $inverse = false)
    {
        $relation = parent::morphedByMany($related, $name, $table, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey, $inverse);

        $relation->withPivot('dimensionable_type');

        return $relation;
    }
}
