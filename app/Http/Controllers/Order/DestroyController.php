<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DestroyController extends Controller
{
    public function __invoke(Order $order)
    {
        $order->purchases()->delete();
        $order->labels()->detach();

        $order->delete();

        return response([]);
    }
}
