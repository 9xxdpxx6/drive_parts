<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    const WRITE_OFF_NOTHING = 1;
    const WRITE_OFF_SUCCESS = 2;
    const WRITE_OFF_FAIL = 3;

    protected $table = 'order_statuses';
    protected $guarded = false;

    static function getWriteOffs()
    {
        return [
            self::WRITE_OFF_NOTHING => 'Не списывать товары',
            self::WRITE_OFF_SUCCESS => 'Списывать товары',
            self::WRITE_OFF_FAIL => 'Зачислять товары',
        ];
    }

    public function getWriteOffTitleAttribute()
    {
        return self::getWriteOffs()[$this->write_off];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
