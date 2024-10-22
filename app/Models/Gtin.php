<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gtin extends Model
{
    use HasFactory;

    protected $fillable = ['gtin', 'status', 'lease_end_date', 'product_id'];

    protected $casts = [
        'gtin' => 'string',
        'status' => 'string',
        'lease_end_date' => 'date',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
