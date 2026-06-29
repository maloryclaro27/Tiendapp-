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
                'observations' => 'Caja de 12 unidades de leche entera de 1 litro para distribucion.',
                'quantity_in_inventory' => 45,
                'inventory_updated_days_ago' => 17,
            ],
            [
                'brand' => 'ALPINA-001',
                'name' => 'Yogurt Fresa Familiar',
                'unit_of_measure' => 'Unidad',
                'observations' => 'Producto refrigerado de alta rotacion en punto de venta.',
                'quantity_in_inventory' => 18,
                'inventory_updated_days_ago' => 6,
            ],
            [
                'brand' => 'COLANTA-002',
                'name' => 'Queso Campesino',
                'unit_of_measure' => 'Unidad',
                'observations' => 'Producto lacteo empacado al vacio, requiere cadena de frio.',
                'quantity_in_inventory' => 0,
                'inventory_updated_days_ago' => 15,
            ],
            [
                'brand' => 'NUTRESA-003',
                'name' => 'Chocolate de Mesa Tradicional',
                'unit_of_measure' => 'Display',
                'observations' => 'Display comercial para exhibicion en gondola.',
                'quantity_in_inventory' => 32,
                'inventory_updated_days_ago' => 10,
            ],
            [
                'brand' => 'POSTOBON-004',
                'name' => 'Gaseosa Manzana x 30',
                'unit_of_measure' => 'Caja',
                'observations' => 'Caja retornable para canal tradicional.',
                'quantity_in_inventory' => 64,
                'inventory_updated_days_ago' => 5,
            ],
            [
                'brand' => 'FAMILIA-005',
                'name' => 'Papel Higienico Familiar',
                'unit_of_measure' => 'Display',
                'observations' => 'Display de producto de aseo para venta por volumen.',
                'quantity_in_inventory' => 11,
                'inventory_updated_days_ago' => 4,
            ],
            [
                'brand' => 'NOEL-006',
                'name' => 'Galletas Surtidas',
                'unit_of_measure' => 'Caja',
                'observations' => 'Caja de galletas surtidas para canal institucional.',
                'quantity_in_inventory' => 27,
                'inventory_updated_days_ago' => 16,
            ],
            [
                'brand' => 'ZENU-007',
                'name' => 'Salchichas Tradicionales',
                'unit_of_measure' => 'Unidad',
                'observations' => 'Producto carnico refrigerado con rotacion semanal.',
                'quantity_in_inventory' => 8,
                'inventory_updated_days_ago' => 8,
            ],
            [
                'brand' => 'RAMO-008',
                'name' => 'Chocoramo Display',
                'unit_of_measure' => 'Display',
                'observations' => 'Display de producto individual para exhibicion comercial.',
                'quantity_in_inventory' => 0,
                'inventory_updated_days_ago' => 11,
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
                    'inventory_updated_at' => now()->subDays($product['inventory_updated_days_ago']),
                ]
            );
        }
    }
}
