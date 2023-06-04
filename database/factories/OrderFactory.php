<?php

namespace Database\Factories;

use App\Http\Resources\User\UserResource;
use App\Models\Car;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $users = UserResource::collection(User::all());
        $userIds = $users->pluck('id')->toArray();
        $statuses = OrderStatus::all()->pluck('id')->toArray();

        $carIds = collect([]);
        foreach ($users as $user) {
            $carIds = $carIds->concat($user->cars->pluck('id'));
        }

        return [
            'user_id' => $this->faker->randomElement($userIds),
            'car_id' => $this->faker->randomElement($carIds->toArray()),
            'is_paid' => $this->faker->boolean,
            'total_price' => $this->faker->randomFloat(2, 1000, 1000000),
            'discount' => $this->faker->randomFloat(2, 0, 1000),
            'status_id' => $this->faker->randomElement($statuses),
            'payment_method' => random_int(1, 4),
            'comment' => $this->faker->text(20),
            'address' => $this->faker->address,
            'delivery' => random_int(1, 3),
            'delivery_price' => $this->faker->randomFloat(2, 100, 1000),
            'note' => $this->faker->text(50),
            'manager_id' => 1,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            if ($order->delivery === 1) {
                $order->update(['delivery_price' => 0]);
            }
        });
    }
}


