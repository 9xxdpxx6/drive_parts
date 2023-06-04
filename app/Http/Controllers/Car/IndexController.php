<?php

namespace App\Http\Controllers\Car;

use App\Http\Controllers\Controller;
use App\Http\Resources\Car\CarResource;
use App\Models\Car;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $cars = Car::all();
        return CarResource::collection($cars);
    }
}
