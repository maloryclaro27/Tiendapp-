<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'brand' => 'ALPINA-001',
                'name' => 'Leche Entera x 12',
                'unit_of_measure' => 'Caja',
                'observations' => 'Caja de 12 unidades de leche entera de 1 litro para distribución.',
                'quantity_in_inventory' => 45,
            ],
            [
                'brand' => 'ALPINA-001',
                'name' => 'Yogurt Fresa Familiar',
                'unit_of_measure' => 'Unidad',
                'observations' => 'Producto refrigerado de alta rotación en punto de venta.',
                'quantity_in_inventory' => 18,
            ],
            [
                'brand' => 'COLANTA-002',
                'name' => 'Queso Campesino',
                'unit_of_measure' => 'Unidad',
                'observations' => 'Producto lácteo empacado al vacío, requiere cadena de frío.',
                'quantity_in_inventory' => 0,
            ],
            [
                'brand' => 'NUTRESA-003',
                'name' => 'Chocolate de Mesa Tradicional',
                'unit_of_measure' => 'Display',
                'observations' => 'Display comercial para exhibición en góndola.',
                'quantity_in_inventory' => 32,
            ],
            [
                'brand' => 'POSTOBON-004',
                'name' => 'Gaseosa Manzana x 30',
                'unit_of_measure' => 'Caja',
                'observations' => 'Caja retornable para canal tradicional.',
                'quantity_in_inventory' => 64,
            ],
            [
                'brand' => 'FAMILIA-005',
                'name' => 'Papel Higiénico Familiar',
                'unit_of_measure' => 'Display',
                'observations' => 'Display de producto de aseo para venta por volumen.',
                'quantity_in_inventory' => 11,
            ],
            [
                'brand' => 'NOEL-006',
                'name' => 'Galletas Surtidas',
                'unit_of_measure' => 'Caja',
                'observations' => 'Caja de galletas surtidas para canal institucional.',
                'quantity_in_inventory' => 27,
            ],
            [
                'brand' => 'ZENU-007',
                'name' => 'Salchichas Tradicionales',
                'unit_of_measure' => 'Unidad',
                'observations' => 'Producto cárnico refrigerado con rotación semanal.',
                'quantity_in_inventory' => 8,
            ],
            [
                'brand' => 'RAMO-008',
                'name' => 'Chocoramo Display',
                'unit_of_measure' => 'Display',
                'observations' => 'Display de producto individual para exhibición comercial.',
                'quantity_in_inventory' => 0,
            ],
        ];

        foreach ($products as $product) {
            $brand = Brand::where('reference', $product['brand'])->first();

            if (! $brand) {
                continue;
            }

            Product::updateOrCreate(
                [
                    'brand_id' => $brand->id,
                    'name' => $product['name'],
                ],
                [
                    'unit_of_measure' => $product['unit_of_measure'],
                    'observations' => $product['observations'],
                    'quantity_in_inventory' => $product['quantity_in_inventory'],
                    'inventory_updated_at' => now()->subDays(rand(1, 15)),
                ]
            );
        }
    }
}