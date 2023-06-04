<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\CarModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $brands = Brand::where('has_vehicles', true)->pluck('id')->toArray();

        return [
            'name' => $this->faker->colorName,
            'alias' => $this->faker->slug,
            'brand_id' => $this->faker->randomElement($brands),
            'is_active' => $this->faker->boolean(90),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (CarModel $carModel) {
            if ($carModel->brand_id === 0) {
                $brand = Brand::where('has_vehicles', true)->inRandomOrder()->first();
                $carModel->update(['brand_id' => $brand->id]);
            }
        });
    }
}
