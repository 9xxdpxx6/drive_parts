<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DestroyController extends Controller
{
    public function __invoke(Product $product)
    {
        Storage::disk('public')->delete($product->image_path);
        $productImages = ProductImage::where('product_id', $product->id)->get();
        foreach ($productImages as $productImage) {
            Storage::disk('public')->delete($productImage->path);
        }
        $product->productImages()->delete();

        $product->delete();

        return response([]);
    }
}
