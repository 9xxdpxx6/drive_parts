<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\UpdateRequest;
use App\Models\Order;
use App\Models\Purchase;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Order $order)
    {
        $data = $request->validated();

        $purchases = collect($data['purchases']);
        $oldPurchases = collect($order->purchases);
        $labelIds = $data['labels'];

        unset($data['purchases'], $data['labels']);

        $order->update($data);

        $order->labels()->sync($labelIds);

        $currentPurchases = $purchases->whereIn('product_id', $oldPurchases->pluck('product_id'))->all();
        $currentPurchases = collect($currentPurchases)->merge($purchases)->unique();

        $purchasesForDelete = $oldPurchases->whereNotIn('id', $currentPurchases->pluck('id'))->all();
        foreach ($purchasesForDelete as $purchase) {
            $purchase->delete();
        }

        foreach ($currentPurchases as $purchase) {
            $purchase = (object)$purchase;
            dump($purchase);

            Purchase::updateOrCreate([
                'product_id' => $purchase->product_id,
                'order_id' => $order->id,
            ], [
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
