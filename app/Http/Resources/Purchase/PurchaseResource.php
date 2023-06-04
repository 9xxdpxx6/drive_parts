<?php

namespace App\Http\Resources\Purchase;

use App\Http\Resources\Label\LabelResource;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Provider\ProviderResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
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
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'product' => new ProductResource($this->product),
            'price' => $this->price,
            'qty' => $this->qty,
            'purchase_price' => $this->purchase_price,
            'provider_id' => $this->provider_id,
            'provider' => new ProviderResource($this->provider),
            'label_id' => $this->label_id,
            'label' => new LabelResource($this->label),
        ];
    }
}
