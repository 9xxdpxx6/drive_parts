<?php

namespace App\Http\Controllers\Order\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $deliveries = Order::getDeliveries();
        return $deliveries;
    }
}
