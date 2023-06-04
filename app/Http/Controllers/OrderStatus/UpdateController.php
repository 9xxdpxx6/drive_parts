<?php

namespace App\Http\Controllers\OrderStatus;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStatus\UpdateRequest;
use App\Models\OrderStatus;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, OrderStatus $orderStatus)
    {
        $data = $request->validated();
        $orderStatus->update($data);

        return response([]);
    }
}
