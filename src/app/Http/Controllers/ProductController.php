<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of the products with filters and sorting.
     */
    public function index(Request $request): JsonResponse
    {
        // Валидация входных параметров
        $validated = $request->validate([
            'q' => 'nullable|string|max:255',
            'price_from' => 'nullable|numeric|min:0',
            'price_to' => 'nullable|numeric|min:0',
            'category_id' => 'nullable|integer|exists:categories,id',
            'in_stock' => 'nullable|boolean',
            'rating_from' => 'nullable|numeric|min:0|max:5',
            'sort' => 'nullable|string|in:price_asc,price_desc,rating_desc,newest',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        // Построение запроса с использованием scopes
        $query = Product::query()
            ->with('category')
            ->search($validated['q'] ?? null)
            ->priceRange(
                $validated['price_from'] ?? null,
                $validated['price_to'] ?? null
            )
            ->byCategory($validated['category_id'] ?? null)
            ->inStock($validated['in_stock'] ?? null)
            ->minRating($validated['rating_from'] ?? null)
            ->sortBy($validated['sort'] ?? null);

        // Пагинация
        $perPage = $validated['per_page'] ?? 15;
        $products = $query->paginate($perPage);

        return response()->json($products);
    }
}
