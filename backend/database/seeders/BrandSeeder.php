<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Alpina', 'reference' => 'ALPINA-001'],
            ['name' => 'Colanta', 'reference' => 'COLANTA-002'],
            ['name' => 'Nutresa', 'reference' => 'NUTRESA-003'],
            ['name' => 'Postobon', 'reference' => 'POSTOBON-004'],
            ['name' => 'Familia', 'reference' => 'FAMILIA-005'],
            ['name' => 'Noel', 'reference' => 'NOEL-006'],
            ['name' => 'Zenu', 'reference' => 'ZENU-007'],
            ['name' => 'Ramo', 'reference' => 'RAMO-008'],
        ];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['reference' => $brand['reference']],
                ['name' => $brand['name']]
            );
        }
    }
}