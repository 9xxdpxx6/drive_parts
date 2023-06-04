<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $length = $this->faker->numberBetween(6, 10);
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';


        $categories = Category::all()->pluck('id')->toArray();
        $brands = Brand::all()->pluck('id')->toArray();

        return [
            'part_number' => $this->faker->lexify(str_repeat('?', $length), [
                '?' => $this->faker->randomElement(str_split($characters))
            ]),
            'name' => $this->faker->text(50),
            'alias' => $this->faker->unique()->slug,
            'description' => $this->faker->text,
            'preview_image' => $this->faker->imageUrl,
            'price' => $this->faker->randomFloat(2, 100, 100000),
            'old_price' => $this->faker->optional($weight = 0.2)->randomFloat(2, 100, 100000),
            'qty' => random_int(0, 999),
            'purchase_price' => $this->faker->optional($weight = 0.9)->randomFloat(2, 100, 100000),
            'category_id' => array_rand($categories),
            'brand_id' => array_rand($brands),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
