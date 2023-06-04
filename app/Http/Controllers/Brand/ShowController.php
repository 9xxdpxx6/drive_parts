<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Http\Resources\Brand\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __invoke(Brand $brand)
    {
        return new BrandResource($brand);
    }
}
