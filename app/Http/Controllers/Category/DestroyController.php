<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class DestroyController extends Controller
{
    public function __invoke(Category $category)
    {
        if (isset($category->image_path)) {
            Storage::disk('public')->delete($category->image_path);
        }
        $category->delete();

        return response([]);
    }
}
