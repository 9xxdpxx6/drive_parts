<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $table = 'providers';
    protected $guarded = false;

    public function resolveRouteBinding($value, $field = null)
    {
        if (is_numeric($value)) {
            return $this->where('id', $value)->firstOrFail();
        }

        return $this->where('code', $value)->firstOrFail();
    }

    public function getRouteKeyName()
    {
        return 'code';
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
