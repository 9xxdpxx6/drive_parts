<?php

namespace App\Http\Controllers\CarModel;

use App\Http\Controllers\Controller;
use App\Models\CarModel;
use Illuminate\Http\Request;

class DestroyController extends Controller
{
    public function __invoke(CarModel $carModel)
    {
        $carModel->delete();

        return response([]);
    }
}
