<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $table = 'cars';
    protected $guarded = false;

    public function carModel()
    {
        return $this->belongsTo(CarModel::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class);
    }

    public function getBrand()
    {
        return Brand::find($this->carModel->brand->id);
    }
}
