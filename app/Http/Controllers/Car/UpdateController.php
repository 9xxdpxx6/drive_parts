<?php

namespace App\Http\Controllers\Car;

use App\Http\Controllers\Controller;
use App\Http\Requests\Car\UpdateRequest;
use App\Models\Car;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Car $car)
    {
        $data = $request->validated();

        unset($data['car_id']);

        $car->update($data);

        return response([]);
    }
}
