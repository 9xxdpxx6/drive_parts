<?php

namespace App\Http\Resources\Car;

use App\Http\Resources\Brand\IndexBrandResource;
use App\Http\Resources\CarModel\CarModelResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
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
            'name' => $this->getBrand()->name . ' ' . $this->carModel->name . ' ' . $this->year,
            'vin_code' => $this->vin_code,
            'car_model_id' => $this->car_model_id,
            'car_model' => new CarModelResource($this->carModel),
            'brand' => new IndexBrandResource($this->getBrand()),
            'modification' => $this->modification,
            'year' => $this->year,
            'customer_id' => $this->user_id,
            'is_active' => $this->is_active,
        ];
    }
}
