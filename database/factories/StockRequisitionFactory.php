<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockRequisition>
 */
class StockRequisitionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'requisition_number' => 'REQ-' . $this->faker->unique()->randomNumber(5),
            'requester_id' => User::factory(), // Otomatis membuat user baru untuk requester
            'request_date' => now(),
            'status' => 'pending',
        ];
    }
}