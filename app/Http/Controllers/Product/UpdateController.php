<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\UpdateRequest;
use App\Models\Product;
use App\Models\ProductImage;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Product $product)
    {
        $data = $request->validated();

        $productImages = $data['images'] ?? null;
        $imagesForDelete = $data['images_for_delete'] ?? null;

        unset($data['images'], $data['images_for_delete'], $data['product_id']);

        $currentImages = $product->productImages;
        if ($imagesForDelete != null) {
            foreach ($currentImages as $currentImage) {
                if (in_array($currentImage->id, $imagesForDelete)) {
                    Storage::disk('public')->delete($currentImage->path);
                    $currentImage->delete();
                }
            }
        }

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
        }

        $product->update($data);

        $firstProductImage = ProductImage::where('product_id', $product->id)->first();
        if ($firstProductImage) {

            $preview = $firstProductImage;

            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            $previewName = str_replace('images/products/', '', $preview->path);
            $path = $preview->path;
            $preview = new UploadedFile(storage_path('app/public/' . $path), $preview->path);;
            $resizedPreviewPath = 'images/products/previews/' . 'prev_' . $previewName;
            $resizedPreview = Image::make($preview)->fit(200, 200);
            Storage::disk('public')->put($resizedPreviewPath, $resizedPreview->encode());
            $product->image_path = $resizedPreviewPath;
            $product->preview_image = url('/storage/' . $resizedPreviewPath);

        } else {
            $product->image_path = '';
            $product->preview_image = '';
        }

        $product->update();

        return response([]);
    }
}
