<?php

namespace Database\Factories;

use App\Models\PurchaseRequest;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseOrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'po_number' => 'PO-' . $this->faker->unique()->randomNumber(6),
            'purchase_request_id' => PurchaseRequest::factory(),
            'supplier_id' => Supplier::factory(),
            'order_by_id' => User::factory(),
            'order_date' => now(),
            'status' => 'sent',
            'total_amount' => $this->faker->numberBetween(100000, 500000),
        ];
    }
}