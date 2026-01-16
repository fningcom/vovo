<?php

namespace App\Enums;

use Illuminate\Database\Eloquent\Builder;

enum ProductSort: string
{
    case PRICE_ASC    = 'price_asc';
    case PRICE_DESC   = 'price_desc';
    case RATING_DESC  = 'rating_desc';
    case NEWEST       = 'newest';

    public function apply(Builder $query): Builder
    {
        return match ($this) {
            self::PRICE_ASC   => $query->orderBy('price'),
            self::PRICE_DESC  => $query->orderByDesc('price'),
            self::RATING_DESC => $query->orderByDesc('rating'),
            self::NEWEST      => $query->orderByDesc('created_at'),
        };
    }

    public static function fromRequest(?string $value): ?self
    {
        return $value ? self::tryFrom($value) : null;
    }
}
