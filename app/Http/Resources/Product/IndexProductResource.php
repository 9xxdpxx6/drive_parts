<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Brand\BrandMinResource;
use App\Http\Resources\Category\CategoryMinResource;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexProductResource extends JsonResource
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
            'part_number' => $this->part_number,
            'name' => $this->name,
            'alias' => $this->alias,
            'description' => $this->description,
            'preview_image' => $this->preview_image,
            'price' => $this->price,
            'old_price' => $this->old_price,
            'qty' => $this->qty,
            'purchase_price' => $this->purchase_price,
            'category_id' => $this->category_id,
            'category' => new CategoryMinResource($this->category),
            'brand_id' => $this->brand_id,
            'brand' => new BrandMinResource($this->brand),
            'is_active' => $this->is_active,
        ];
    }
}
