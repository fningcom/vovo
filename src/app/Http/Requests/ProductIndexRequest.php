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
            'price_from' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    $priceTo = $this->input('price_to');
                    if ($priceTo !== null && $value > $priceTo) {
                        $fail('The '.$attribute.' must not be greater than price to.');
                    }
                },
            ],
            'price_to' => 'nullable|numeric|min:0',
            'category_id' => 'nullable|integer|exists:categories,id',
            'in_stock' => 'nullable|boolean',
            'rating_from' => 'nullable|numeric|min:0|max:5',
            'sort' => ['nullable', new Enum(ProductSort::class)],
            'per_page' => 'nullable|integer|min:1|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'price_from.numeric' => 'The minimum price must be a number.',
            'price_to.numeric' => 'The maximum price must be a number.',
        ];
    }
}
