<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Label;
use App\Models\Order;
use App\Models\OrderStatus;

class FilterListController extends Controller
{
    public function __invoke()
    {
        $statuses = OrderStatus::all();
        $labels = Label::all();

        $maxDate = Order::orderBy('created_at', 'DESC')->first()->created_at;
        $minDate = Order::orderBy('created_at', 'ASC')->first()->created_at;

        $result = [
            'statuses' => $statuses,
            'labels' => $labels,
            'dates' => [
                'max' => $maxDate,
                'min' => $minDate,
            ],
        ];

        return response()->json($result);
    }
}
