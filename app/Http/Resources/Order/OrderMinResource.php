<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Car\CarResource;
use App\Http\Resources\Label\LabelResource;
use App\Http\Resources\OrderStatus\OrderStatusResource;
use App\Http\Resources\Purchase\PurchaseResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use function Symfony\Component\Translation\t;

class OrderMinResource extends JsonResource
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
            'user_id' => $this->user_id,
            'car_id' => $this->car_id,
            'is_paid' => $this->is_paid,
            'total_price' => $this->total_price,
            'discount' => $this->discount,
            'status_id' => $this->status_id,
            'status' => new OrderStatusResource($this->status),
            'labels' => LabelResource::collection($this->labels),
            'payment_method_id' => $this->payment_method,
            'payment_method_name' => $this->payment_method,
            'comment' => $this->comment,
            'address' => $this->address,
            'delivery_id' => $this->delivery,
            'delivery_name' => $this->deliveryName,
            'delivery_price' => $this->delivery_price,
            'manager_id' => $this->manager_id,
        ];
    }
}
