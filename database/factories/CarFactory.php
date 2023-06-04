<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\CarModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $carModels = CarModel::all()->pluck('id')->toArray();
        $users = User::all()->pluck('id')->toArray();

        return [
            'vin_code' => strtoupper($this->faker->unique()->regexify('[A-HJ-NPR-Z0-9]{17}')),
            'car_model_id' => array_rand($carModels),
            'modification' => $this->faker->text(50),
            'year' => $this->faker->year,
            'user_id' => array_rand($users),
            'is_active' => $this->faker->boolean(90),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Car $car) {
            if ($car->car_model_id === 0) {
                $carModel = CarModel::inRandomOrder()->first();
                $car->update(['car_model_id' => $carModel->id]);
            }
        });
    }
}
