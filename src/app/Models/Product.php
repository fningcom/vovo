<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'price',
        'category_id',
        'in_stock',
        'rating',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'in_stock' => 'boolean',
        'rating' => 'float',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope a query to search products by name.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if ($search) {
            return $query->where('name', 'LIKE', "%{$search}%");
        }
        return $query;
    }

    /**
     * Scope a query to filter products by price range.
     */
    public function scopePriceRange(Builder $query, ?float $priceFrom, ?float $priceTo): Builder
    {
        if ($priceFrom !== null) {
            $query->where('price', '>=', $priceFrom);
        }
        if ($priceTo !== null) {
            $query->where('price', '<=', $priceTo);
        }
        return $query;
    }

    /**
     * Scope a query to filter products by category.
     */
    public function scopeByCategory(Builder $query, ?int $categoryId): Builder
    {
        if ($categoryId) {
            return $query->where('category_id', $categoryId);
        }
        return $query;
    }

    /**
     * Scope a query to filter products by stock status.
     */
    public function scopeInStock(Builder $query, ?bool $inStock): Builder
    {
        if ($inStock !== null) {
            return $query->where('in_stock', $inStock);
        }
        return $query;
    }

    /**
     * Scope a query to filter products by minimum rating.
     */
    public function scopeMinRating(Builder $query, ?float $ratingFrom): Builder
    {
        if ($ratingFrom !== null) {
            return $query->where('rating', '>=', $ratingFrom);
        }
        return $query;
    }

    /**
     * Scope a query to sort products.
     */
    public function scopeSortBy(Builder $query, ?string $sort): Builder
    {
        return match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'rating_desc' => $query->orderBy('rating', 'desc'),
            'newest' => $query->orderBy('created_at', 'desc'),
            default => $query->orderBy('id', 'asc'),
        };
    }
}
