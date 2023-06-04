<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $orders = Order::all()->pluck('id')->toArray();
        $products = Product::all()->pluck('id')->toArray();
        $providers = Provider::all()->pluck('id')->toArray();
        $labels = Provider::all()->pluck('id')->toArray();

        return [
            'order_id' => array_rand($orders),
            'product_id' => array_rand($products),
            'price' => $this->faker->randomFloat(2, 100, 100000),
            'qty' => random_int(1, 7),
            'purchase_price' => $this->faker->randomFloat(2, 100, 100000),
            'provider_id' => array_rand($providers),
            'label_id' => array_rand($labels),
        ];
    }
}
