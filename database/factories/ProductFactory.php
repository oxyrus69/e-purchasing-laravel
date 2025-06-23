<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'PROD-' . $this->faker->unique()->randomNumber(4),
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence,
            'unit' => 'Pcs',
            'stock' => 100,
            'minimum_stock' => 10,
        ];
    }
}
