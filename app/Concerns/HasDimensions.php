<?php

namespace App\Concerns;

use App\Models\Dimension;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasDimensions
{
    public function dimensions(): MorphToMany
    {
        return $this->morphToMany(Dimension::class, 'dimensionable')->withTimestamps()->withPivot('dimensionable_type');
    }

    public function addDimension(Dimension $dimension): void
    {
        $this->dimensions()->save($dimension, ['dimensionable_type' => get_class($this)]);
    }

    public function removeDimension(Dimension $dimension): void
    {
        $this->dimensions()->detach($dimension);
    }
}
