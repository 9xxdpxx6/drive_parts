<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    use Filterable;

    const DELIVERY_PICKUP = 1;
    const DELIVERY_EXPRESS = 2;
    const DELIVERY_CARGO = 3;

    const PAYMENT_METHOD_CASH = 1;
    const PAYMENT_METHOD_TERMINAL = 2;
    const PAYMENT_METHOD_BANK_TRANSFER = 3;
    const PAYMENT_METHOD_CARD = 4;

    protected $table = 'orders';
    protected $guarded = false;

    static function getDeliveries() {
        return [
            self::DELIVERY_PICKUP => 'Самовывоз',
            self::DELIVERY_EXPRESS => 'Курьерская доставка',
            self::DELIVERY_CARGO => 'Доставка в другой город',
        ];
    }

    static function getPaymentMethods() {
        return [
            self::PAYMENT_METHOD_CASH => 'Наличные',
            self::PAYMENT_METHOD_TERMINAL => 'Через терминал',
            self::PAYMENT_METHOD_BANK_TRANSFER => 'Система быстрых платежей',
            self::PAYMENT_METHOD_CARD => 'Банковская карта',
        ];
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'purchases', 'order_id', 'product_id');
    }

    public function getDeliveryNameAttribute() {
        return self::getDeliveries()[$this->delivery];
    }

    public function getPaymentMethodNameAttribute() {
        return self::getPaymentMethods()[$this->payment_method];
    }
}
