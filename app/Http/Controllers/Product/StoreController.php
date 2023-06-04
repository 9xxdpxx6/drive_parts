<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreRequest;
use App\Models\Product;
use App\Models\ProductImage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();

        $productImages = $data['images'] ?? null;

        unset($data['images']);

        $product = Product::firstOrCreate($data);

        if ($productImages != null) {

            foreach ($productImages as $productImage) {
                $resizedImage = Image::make($productImage)->widen(1000);
                $resizedImagePath = 'images/products/' . Carbon::now()->timestamp . '_' . $productImage->getClientOriginalName();
                Storage::disk('public')->put($resizedImagePath, $resizedImage->encode());

                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $resizedImagePath,
                    'url' => url('/storage/' . $resizedImagePath)
                ]);
            }

            $preview = $productImages[0];

            $resizedPreview = Image::make($preview)->fit(200, 200);
            $resizedPreviewPath = 'images/products/previews/' . Carbon::now()->timestamp . '_prev_' . $preview->getClientOriginalName();
            Storage::disk('public')->put($resizedPreviewPath, $resizedPreview->encode());

            $product->image_path = $resizedPreviewPath;
            $product->preview_image = url('/storage/' . $resizedPreviewPath);
            $product->update();
        }

        return response([]);
    }
}
