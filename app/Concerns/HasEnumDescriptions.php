<?php

namespace App\Concerns;

use Illuminate\Support\Str;

trait HasEnumDescriptions
{
    public static function getLabel(string $value): string
    {
        return static::descriptions()[$value]
            ?? Str::title(str_replace(['-', '_'], ' ', $value));
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    abstract public static function descriptions(): array;
}
