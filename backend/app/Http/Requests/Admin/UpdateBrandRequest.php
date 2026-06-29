<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $brandId = $this->route('brand')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:150',
            ],
            'reference' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Za-z0-9-]+$/',
                Rule::unique('brands', 'reference')->ignore($brandId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la marca es obligatorio.',
            'name.max' => 'El nombre de la marca no puede superar los 150 caracteres.',
            'reference.required' => 'La referencia de la marca es obligatoria.',
            'reference.max' => 'La referencia no puede superar los 50 caracteres.',
            'reference.regex' => 'La referencia solo puede contener letras, numeros y guiones.',
            'reference.unique' => 'Ya existe otra marca con esta referencia.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre de la marca',
            'reference' => 'referencia',
        ];
    }
}