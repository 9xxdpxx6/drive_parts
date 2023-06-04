<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brand\StoreRequest;
use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();

        if (isset($data['preview_image'])) {
            $name = Carbon::now()->timestamp . '_' . $data['preview_image']->getClientOriginalName();
            $data['image_path'] = Storage::disk('public')->putFileAs('/images/brands', $data['preview_image'], $name);
            $data['preview_image'] = url('/storage/' . $data['image_path']);
            $imagePath = storage_path('app/public/' . $data['image_path']);

            Image::make($imagePath)->fit(200, 200)->save($imagePath);
        }

        Brand::firstOrCreate($data);

        return response([]);
    }
}
