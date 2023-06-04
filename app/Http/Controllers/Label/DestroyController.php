<?php

namespace App\Http\Controllers\Label;

use App\Http\Controllers\Controller;
use App\Models\Label;
use Illuminate\Http\Request;

class DestroyController extends Controller
{
    public function __invoke(Label $label)
    {
        $label->delete();

        return response([]);
    }
}
