<?php

namespace App\Http\Controllers\Car;

use App\Http\Controllers\Controller;
use App\Http\Resources\Car\CarResource;
use App\Models\Car;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __invoke(Car $car)
    {
        return new CarResource($car);
    }
}
