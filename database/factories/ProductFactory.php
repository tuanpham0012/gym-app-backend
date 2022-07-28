<?php

namespace Database\Factories;

use App\Models\ProductType;
use Illuminate\Support\Str;
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
    public function definition()
    {
        return [
            'product_code' => Str::random(6),
            'name' => fake()->name(),
            'price' => fake()->numberBetween($min = 100000, $max = 900000),
            'purchase_price' => fake()->numberBetween($min = 90000, $max = 899000),
            'quantity' => fake()->numberBetween($min = 100, $max = 999),
            'description' => fake()->text($maxNbChars = 200),
            'product_type_id' => ProductType::query()->inRandomOrder()->value('id') ?? '1',
        ];
    }
}
