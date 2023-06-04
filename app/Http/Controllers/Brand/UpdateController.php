<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brand\UpdateRequest;
use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Brand $brand)
    {
        $data = $request->validated();

        unset($data['brand_id']);

        if ($brand->alias == $data['alias']) {
            unset($data['alias']);
        }

        if (isset($data['image_for_delete'])) {
            Storage::disk('public')->delete($brand->image_path);
            $brand->image_path = '';
            $brand->preview_image = '';
        }

        if (isset($data['preview_image'])) {
            $name = Carbon::now()->timestamp . '_' . $data['preview_image']->getClientOriginalName();
            $data['image_path'] = Storage::disk('public')->putFileAs('/images/brands', $data['preview_image'], $name);
            $data['preview_image'] = url('/storage/' . $data['image_path']);
            $imagePath = storage_path('app/public/' . $data['image_path']);

            Image::make($imagePath)->fit(200, 200)->save($imagePath);
        }
        unset($data['image_for_delete']);

        $brand->update($data);

        return response([]);
    }
}
