<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands';
    protected $guarded = false;

    public function resolveRouteBinding($value, $field = null)
    {
        if (is_numeric($value)) {
            return $this->where('id', $value)->firstOrFail();
        }

        return $this->where('alias', $value)->firstOrFail();
    }

    public function getRouteKeyName()
    {
        return 'alias';
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function carModels()
    {
        return $this->hasMany(CarModel::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->preview_image ? url('storage/' . $this->preview_image) : null;
    }
}
