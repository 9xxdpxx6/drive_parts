<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProviderFactory extends Factory
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
            'code' => strtoupper($this->faker->unique()->lexify('???')),
            'color' => $this->faker->hexColor,
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
