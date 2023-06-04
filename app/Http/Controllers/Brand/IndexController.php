<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Http\Resources\Brand\IndexBrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $brands = Brand::all();
        return IndexBrandResource::collection($brands);
    }
}
