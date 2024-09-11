<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\Status;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {

        $status = Status::values();

        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'stock_quantity' => $this->faker->numberBetween(0, 1000),
            'price' => $this->faker->randomFloat(2, 1, 10000),
            'status' => $this->faker->randomElement($status)
        ];
    }
}
