<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductIndexRequest;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(ProductIndexRequest $request, ProductService $service): JsonResponse
    {
        $filters = $request->validated();
        
        try {
            $products = $service->paginate($filters);
            
            return response()->json($products);
        } catch (\Exception $e) {
            Log::error('Product index request failed', [
                'error' => $e->getMessage(),
                'filters' => $filters
            ]);
            
            return response()->json([
                'error' => 'An error occurred while fetching products'
            ], 500);
        }
    }
}
