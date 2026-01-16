<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    public function paginate(array $filters): LengthAwarePaginator
    {
        return Product::query()
            ->with('category')
            ->filter($filters)
            ->paginate($filters['per_page'] ?? 15);
    }
}
