<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Catalog extends Model
{
    public $incrementing = false; // Disable auto-incrementing ID since we're using UUID
    protected $keyType = 'string'; // The primary key type is string (UUID)

    protected $fillable = ['channel', 'source'];

    public function getSupplier(string $supplierName)
    {
        return $this->products()->where('supplier', $supplierName)->get();
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
    /**
     * Boot function to generate UUID on model creation.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid(); // Generate UUID
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
