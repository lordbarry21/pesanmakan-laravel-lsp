<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class FoodFactory extends Factory {
    public function definition(): array { return ['name' => 'Menu Test', 'price' => 10000]; }
}
