<?php

namespace App\Http\Controllers\Label;

use App\Http\Controllers\Controller;
use App\Http\Resources\Label\LabelResource;
use App\Models\Label;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $labels = Label::all();

        return LabelResource::collection($labels);
    }
}
