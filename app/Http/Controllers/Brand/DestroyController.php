<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DestroyController extends Controller
{
    public function __invoke(Brand $brand)
    {
        if (isset($brand->image_path)) {
            Storage::disk('public')->delete($brand->image_path);
        }
        $brand->delete();

        return response([]);
    }
}
