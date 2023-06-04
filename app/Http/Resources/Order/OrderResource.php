<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Car\CarResource;
use App\Http\Resources\Label\LabelResource;
use App\Http\Resources\OrderStatus\OrderStatusResource;
use App\Http\Resources\Purchase\PurchaseResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use function Symfony\Component\Translation\t;

class OrderResource extends JsonResource
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
            'date' => $this->created_at,
            'user_id' => $this->user_id,
            'user' => new UserResource($this->user),
            'car_id' => $this->car_id,
            'car' => new CarResource($this->car),
            'is_paid' => $this->is_paid,
            'total_price' => $this->total_price,
            'discount' => $this->discount,
            'status_id' => $this->status_id,
            'status' => new OrderStatusResource($this->status),
            'labels' => LabelResource::collection($this->labels),
            'purchases' => PurchaseResource::collection($this->purchases),
            'payment_method_id' => $this->payment_method,
            'payment_method_name' => $this->payment_method ? $this->paymentMethodName : null,
            'comment' => $this->comment,
            'address' => $this->address,
            'delivery_id' => $this->delivery,
            'delivery_name' => $this->deliveryName,
            'delivery_price' => $this->delivery_price,
            'note' => $this->note,
            'manager_id' => $this->manager_id,
            'manager' => new UserResource($this->manager),
        ];
    }
}
