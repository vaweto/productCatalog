<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

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
            'name' => $this->faker->name,
            'code' => $this->faker->slug,
            'category_id' => Category::factory()->create(),
            'price' => $this->faker->randomFloat(2, 1, 1000000),
            'released_at' => Carbon::now()->addDay(),
        ];
    }
}
