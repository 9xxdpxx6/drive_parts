<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;

class CreateController extends Controller
{
    public function __invoke(Category $category)
    {
        $categories = Category::all();
        $brands = Brand::all();

        return compact('categories', 'brands');
    }
}
