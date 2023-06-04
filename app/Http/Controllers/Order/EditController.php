<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class EditController extends Controller
{
    public function __invoke(Order $order)
    {
        return view('product.edit', compact('order'));
    }
}
