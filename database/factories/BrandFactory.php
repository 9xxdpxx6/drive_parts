<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->colorName,
            'alias' => $this->faker->unique()->slug,
            'preview_image' => $this->faker->imageUrl,
            'has_vehicles' => $this->faker->boolean(10),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
