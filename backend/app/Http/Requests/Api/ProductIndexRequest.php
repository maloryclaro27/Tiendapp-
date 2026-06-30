<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:120'],
            'brand_id' => [
                'nullable',
                'integer',
                Rule::exists('brands', 'id')->whereNull('deleted_at'),
            ],
            'unit_of_measure' => ['nullable', 'string', Rule::in(['Unidad', 'Display', 'Caja'])],
            'availability' => [
                'nullable',
                'string',
                Rule::in([
                    'available',
                    'in_stock',
                    'healthy_stock',
                    'low_stock',
                    'out_of_stock',
                ]),
            ],
            'sort' => [
                'nullable',
                'string',
                Rule::in([
                    'name',
                    'stock_asc',
                    'stock_desc',
                    'updated_asc',
                ]),
            ],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ];
    }
}
