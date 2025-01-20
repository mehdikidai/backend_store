<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $list = ["ssd" => "ssd", "cpu" => "cpu", "gpu" => "gpu"];

        $el = fake()->unique()->randomElement(array_keys($list));

        return [
            'name' => $el,
            'slug' => $list[$el],
        ];
    }
}
