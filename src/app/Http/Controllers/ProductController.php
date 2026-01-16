<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductIndexRequest;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(ProductIndexRequest $request, ProductService $service): JsonResponse
    {
        $filters = $request->validated();

        $products = $service->paginate($filters);

        return response()->json($products);
    }
}
