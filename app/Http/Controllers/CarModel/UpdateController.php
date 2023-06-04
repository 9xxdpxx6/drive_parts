<?php

namespace App\Http\Controllers\CarModel;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarModel\UpdateRequest;
use App\Models\CarModel;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, CarModel $carModel)
    {
        $data = $request->validated();

        unset($data['car_model_id']);

        $carModel->update($data);

        return response([]);
    }
}
