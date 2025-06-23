<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class SupplierFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => 'SUP-' . $this->faker->unique()->randomNumber(4),
            'name' => $this->faker->company,
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
        ];
    }
}