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
            'name' => fake()->unique()->randomElement($this->names),
            'description' => fake()->text(20),
            'price' => fake()->randomFloat(2, 100, 500),
            'stock' => fake()->numberBetween(0, 100),
            'sku' => fake()->unique()->numerify('SKU-########'),
            'category_id' => fake()->numberBetween(1, 3),
        ];

    }

    protected $names = [
        'AMD Ryzen 5 5600',
        'Intel Core i5 14600KF',
        'AMD Ryzen 3 4100',
        'AMD Ryzen 7 5800X',
        'Intel Core i9 13900K',
        'AMD Ryzen 9 5950X',
        'Intel Core i7 12700K',
        'AMD Ryzen 5 5600G',
        'Intel Core i5 12400',
        'AMD Ryzen 3 3200G',
        'Intel Core i3 12100F',
        'AMD Ryzen 7 7700X',
        'Intel Core i9 12900KS',
        'AMD Ryzen 9 7900X',
        'Intel Core i7 13700KF'
    ];
}
