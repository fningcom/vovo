<?php

namespace App\Models;

use App\Enums\ProductSort;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'rating',
        'stock',
        'category_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rating' => 'float',
        'stock' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['q'] ?? null, fn($q, $v) =>
                $q->where('name', 'like', "%{$v}%")
            )
            ->when($filters['price_from'] ?? null, fn($q, $v) =>
                $q->where('price', '>=', $v)
            )
            ->when($filters['price_to'] ?? null, fn($q, $v) =>
                $q->where('price', '<=', $v)
            )
            ->when($filters['category_id'] ?? null, fn($q, $v) =>
                $q->where('category_id', $v)
            )
            ->when(array_key_exists('in_stock', $filters), fn($q) =>
                $q->where('stock', ($filters['in_stock'] ? '>' : '='), 0)
            )
            ->when($filters['rating_from'] ?? null, fn($q, $v) =>
                $q->where('rating', '>=', $v)
            )
            ->when($filters['sort'] ?? null, function ($q, $v) {
                $sortEnum = ProductSort::fromRequest($v);
                $sortEnum?->apply($q);
            });
    }
}
