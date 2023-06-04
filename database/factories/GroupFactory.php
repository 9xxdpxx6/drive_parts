<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->jobTitle,
            'alias' => $this->faker->unique()->slug,
            'discount' => random_int(100, 1000) / 10,
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
