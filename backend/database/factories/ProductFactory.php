<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'brand_id' => Brand::factory(),
            'name' => fake()->words(3, true),
            'unit_of_measure' => fake()->randomElement(['Unidad', 'Display', 'Caja']),
            'observations' => fake()->sentence(12),
            'quantity_in_inventory' => fake()->numberBetween(0, 250),
            'inventory_updated_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}