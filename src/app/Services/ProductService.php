<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class ProductService
{
    private const CACHE_TTL = 300; // 5 minutes
    
    public function __construct(
        private Cache $cache,
    ) {}
    
    public function paginate(array $filters): LengthAwarePaginator
    {
        // Validate filters
        $this->validateFilters($filters);
        
        // Create cache key based on filters
        $cacheKey = $this->generateCacheKey($filters);
        
        return $this->cache->remember($cacheKey, self::CACHE_TTL, function () use ($filters) {
            try {
                return Product::query()
                    ->with('category')
                    ->filter($filters)
                    ->paginate($filters['per_page'] ?? 15);
            } catch (\Exception $e) {
                Log::error('Product pagination failed', [
                    'error' => $e->getMessage(),
                    'filters' => $filters
                ]);
                
                throw $e;
            }
        });
    }
    
    private function validateFilters(array $filters): void
    {
        // Validate price range
        if (isset($filters['price_from'], $filters['price_to'])) {
            if ($filters['price_from'] > $filters['price_to']) {
                throw new \InvalidArgumentException('Price from cannot be greater than price to');
            }
        }
        
        // Validate rating range
        if (isset($filters['rating_from'])) {
            if ($filters['rating_from'] < 0 || $filters['rating_from'] > 5) {
                throw new \InvalidArgumentException('Rating from must be between 0 and 5');
            }
        }
    }
    
    private function generateCacheKey(array $filters): string
    {
        ksort($filters); // Ensure consistent ordering for cache key
        return 'products_' . md5(json_encode($filters));
    }
}
