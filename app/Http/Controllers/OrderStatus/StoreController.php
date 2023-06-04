<?php

namespace App\Http\Controllers\OrderStatus;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStatus\StoreRequest;
use App\Models\OrderStatus;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        OrderStatus::firstOrCreate($data);

        return response([]);
    }
}
