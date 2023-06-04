<?php

namespace App\Http\Resources\OrderStatus;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderStatusResource extends JsonResource
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
            'write_off' => $this->write_off,
            'writeOffTitle' => $this->writeOffTitle,
            'is_active' => $this->is_active,
        ];
    }
}
