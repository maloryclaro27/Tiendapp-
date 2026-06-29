<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brand_id' => [
                'required',
                'integer',
                Rule::exists('brands', 'id')->whereNull('deleted_at'),
            ],
            'name' => [
                'required',
                'string',
                'max:200',
            ],
            'unit_of_measure' => [
                'required',
                'string',
                Rule::in(['Unidad', 'Display', 'Caja']),
            ],
            'observations' => [
                'required',
                'string',
                'max:2000',
            ],
            'quantity_in_inventory' => [
                'required',
                'integer',
                'min:0',
                'max:999999',
            ],
            'inventory_updated_at' => [
                'required',
                'date',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'brand_id.required' => 'La marca es obligatoria.',
            'brand_id.exists' => 'La marca seleccionada no existe.',
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.max' => 'El nombre del producto no puede superar los 200 caracteres.',
            'unit_of_measure.required' => 'La unidad de medida es obligatoria.',
            'unit_of_measure.in' => 'La unidad de medida debe ser Unidad, Display o Caja.',
            'observations.required' => 'Las observaciones son obligatorias.',
            'observations.max' => 'Las observaciones no pueden superar los 2000 caracteres.',
            'quantity_in_inventory.required' => 'La cantidad en inventario es obligatoria.',
            'quantity_in_inventory.integer' => 'La cantidad en inventario debe ser un numero entero.',
            'quantity_in_inventory.min' => 'La cantidad en inventario no puede ser negativa.',
            'inventory_updated_at.required' => 'La fecha de actualizacion del inventario es obligatoria.',
            'inventory_updated_at.date' => 'La fecha de actualizacion del inventario debe ser valida.',
        ];
    }

    public function attributes(): array
    {
        return [
            'brand_id' => 'marca',
            'name' => 'nombre del producto',
            'unit_of_measure' => 'unidad de medida',
            'observations' => 'observaciones',
            'quantity_in_inventory' => 'cantidad en inventario',
            'inventory_updated_at' => 'fecha de actualizacion',
        ];
    }
}
