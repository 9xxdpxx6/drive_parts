<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CategoryResource extends JsonResource
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
            'parent_id' => $this->parent_id,
            'children' => self::collection($this->children),
            'is_active' => $this->is_active,
        ];
    }
}
