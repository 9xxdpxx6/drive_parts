<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $categories = Category::all()->pluck('id')->toArray();
        $categories[] = null;

        return [
            'name' => $this->faker->colorName,
            'alias' => $this->faker->unique()->slug,
            'preview_image' => $this->faker->imageUrl,
            'parent_id' => array_rand($categories),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
