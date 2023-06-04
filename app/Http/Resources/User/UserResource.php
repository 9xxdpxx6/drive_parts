<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Car\CarResource;
use App\Http\Resources\Group\GroupResource;
use App\Http\Resources\Order\OrderMinResource;
use App\Http\Resources\Role\RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'group_id' => $this->group_id,
            'role_id' => $this->role_id,
            'group' => new GroupResource($this->group),
            'role' => new RoleResource($this->role),
            'orders' => OrderMinResource::collection($this->orders),
            'cars' => CarResource::collection($this->cars),
            'info' => $this->info,
            'is_active' => $this->is_active,
        ];
    }
}
