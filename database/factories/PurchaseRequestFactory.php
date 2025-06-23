<?php

namespace Database\Factories;

use App\Models\PurchaseRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseRequestFactory extends Factory
{
    protected $model = PurchaseRequest::class;

    public function definition(): array
    {
        return [
            'pr_number' => 'PR-' . $this->faker->unique()->randomNumber(6),
            'requester_id' => User::factory(),
            'request_date' => now(),
            'status' => 'pending_approval',
        ];
    }
}