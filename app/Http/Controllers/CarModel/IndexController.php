<?php

namespace App\Http\Controllers\CarModel;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarModel\CarModelResource;
use App\Models\CarModel;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $carModels = CarModel::all();

        return CarModelResource::collection($carModels);
    }
}
