<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\UpdateRequest;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Category $category)
    {
        $data = $request->validated();

        unset($data['category_id']);

        if ($category->alias == $data['alias']) {
            unset($data['alias']);
        }

        if (isset($data['image_for_delete'])) {
            Storage::disk('public')->delete($category->image_path);
            $category->image_path = '';
            $category->preview_image = '';
        }

        if (isset($data['preview_image'])) {
            $name = Carbon::now()->timestamp . '_' . $data['preview_image']->getClientOriginalName();
            $data['image_path'] = Storage::disk('public')->putFileAs('/images/categories', $data['preview_image'], $name);
            $data['preview_image'] = url('/storage/' . $data['image_path']);
            $imagePath = storage_path('app/public/' . $data['image_path']);

            Image::make($imagePath)->fit(200, 200)->save($imagePath);
        }
        unset($data['image_for_delete']);

        $category->update($data);

        return response([]);
    }
}
