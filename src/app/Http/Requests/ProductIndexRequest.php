<?php

namespace App\Http\Requests;

use App\Enums\ProductSort;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ProductIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'q' => 'nullable|string|max:255',
            'price_from' => 'nullable|numeric|min:0',
            'price_to' => 'nullable|numeric|min:0',
            'category_id' => 'nullable|integer|exists:categories,id',
            'in_stock' => 'nullable|boolean',
            'rating_from' => 'nullable|numeric|min:0|max:5',
            'sort' => ['nullable', new Enum(ProductSort::class)],
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }
}
