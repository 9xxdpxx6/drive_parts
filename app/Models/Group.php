<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';
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

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
