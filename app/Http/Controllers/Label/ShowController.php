<?php

namespace App\Http\Controllers\Label;

use App\Http\Controllers\Controller;
use App\Http\Resources\Label\LabelResource;
use App\Models\Label;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __invoke(Label $label)
    {
        return new LabelResource($label);
    }
}
