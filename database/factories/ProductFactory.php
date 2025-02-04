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
            'name' => $this->faker->word(),
            'price' => $this->faker->numberBetween($min = 5000, $max = 20000),
            'category_id' => \App\Models\Category::all()->random()->id,
            'description' => $this->faker->sentence(),
            'stock' => $this->faker->numberBetween(1, 100),
            'image_path' => $this->faker->imageUrl(),
        ];
    }
}
