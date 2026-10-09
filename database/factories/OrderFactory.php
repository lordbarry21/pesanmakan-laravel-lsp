<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class OrderFactory extends Factory {
    public function definition(): array { return ['customer_name' => 'Test User', 'total_price' => 20000]; }
}
