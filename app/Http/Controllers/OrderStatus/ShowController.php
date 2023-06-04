<?php

namespace App\Http\Controllers\OrderStatus;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderStatus\OrderStatusResource;
use App\Models\OrderStatus;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __invoke(OrderStatus $orderStatus)
    {
        return new OrderStatusResource($orderStatus);
    }
}
