<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    const PERMISSIONS = [
        'brands' => 'Brand',
        'cars' => 'Car',
        'carModels' => 'CarModel',
        'categories' => 'Category',
        'groups' => 'Group',
        'labels' => 'Label',
        'orders' => 'Order',
        'orderStatuses' => 'OrderStatus',
        'products' => 'Product',
        'providers' => 'Provider',
        'roles' => 'Role',
        'customers' => 'User',
        'managers' => 'User',
    ];

    protected $table = 'permissions';
    protected $guarded = false;

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
