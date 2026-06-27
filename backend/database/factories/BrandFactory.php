<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BrandFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'name' => $name,
            'reference' => strtoupper(Str::slug($name)) . '-' . fake()->unique()->numberBetween(100, 999),
        ];
    }
}