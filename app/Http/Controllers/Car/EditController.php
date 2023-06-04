<?php

namespace App\Http\Controllers\Car;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class EditController extends Controller
{
    public function __invoke(Car $car)
    {
        return view('car.edit', compact('car'));
    }
}
