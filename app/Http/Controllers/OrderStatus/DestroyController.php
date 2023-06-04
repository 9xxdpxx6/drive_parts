<?php

namespace App\Http\Controllers\OrderStatus;

use App\Http\Controllers\Controller;
use App\Models\OrderStatus;
use Illuminate\Http\Request;

class DestroyController extends Controller
{
    public function __invoke(OrderStatus $orderStatus)
    {
        $orderStatus->delete();

        return response([]);
    }
}
