<?php

namespace App\Http\Resources\Brand;

use App\Http\Resources\CarModel\CarModelResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class IndexBrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'alias' => $this->alias,
            'preview_image' => $this->preview_image,
            'image_path' => str_replace('images', '', $this->preview_image),
            'has_vehicles' => $this->has_vehicles,
            'car_models_qty' => $this->carModels->count(),
            'is_active' => $this->is_active,
        ];
    }
}
