<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreRequest;
use App\Models\Order;
use App\Models\Purchase;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();

        $purchases = $data['purchases'];
        $labelIds = $data['labels'];

        unset($data['purchases'], $data['labels']);

        $order = Order::create($data);

        if ($labelIds) {
            $order->labels()->attach($labelIds);
        }

        foreach ($purchases as $purchase) {
            $purchase = (object)$purchase;

            Purchase::create([
                'order_id' => $order->id,
                'product_id' => $purchase->product_id,
                'price' => $purchase->price,
                'qty' => $purchase->qty,
                'purchase_price' => $purchase->purchase_price,
                'provider_id' => $purchase->provider_id ?? null,
                'label_id' => $purchase->label_id ?? null,
            ]);
        }

        return response([]);
    }
}
