<?php

namespace App\Http\Controllers\API\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Order\StoreRequest;
use App\Http\Resources\Order\OrderResource;
use App\Http\Resources\Product\IndexProductResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
return $data;
//        $password = Hash::make('123123123');
//
//        $customer = User::firstOrCreate([
//            'email' => $data['email'],
//        ], [
//            'name' => $data['email'],
//            'address' => $data['email'],
//            'password' => $password,
//        ]);
//
//        $order = Order::create([
//            'products' => json_encode($data['products']),
//            'customer_id' => $customer->id,
//            'total_price' => $data['total_price']
//        ]);
//
//        return new OrderResource($order);
    }
}
