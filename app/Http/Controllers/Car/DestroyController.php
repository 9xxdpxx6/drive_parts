<?php

namespace App\Http\Controllers\Car;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class DestroyController extends Controller
{
    public function __invoke(Car $car)
    {
        $car->delete();

        return response([]);
    }
}
