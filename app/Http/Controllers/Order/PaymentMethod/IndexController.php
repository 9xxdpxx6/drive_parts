<?php

namespace App\Http\Controllers\Order\PaymentMethod;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $paymentMethods = Order::getPaymentMethods();
        return $paymentMethods;
    }
}
