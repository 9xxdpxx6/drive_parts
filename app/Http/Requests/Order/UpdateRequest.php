<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|integer',
            'car_id' => 'nullable|integer',
            'is_paid' => 'required|boolean',
            'total_price' => ['required', 'regex:/^\d{1,10}(\.\d{1,2})?$/'],
            'discount' => ['nullable', 'regex:/^\d{1,10}(\.\d{1,2})?$/'],
            'status_id' => 'required|integer',
            'labels' => 'nullable|array',
            'payment_method' => 'nullable|integer',
            'purchases' => 'required|array',
            'purchases.*.product_id' => 'required|integer',
            'purchases.*.price' => ['required', 'regex:/^\d{1,10}(\.\d{1,2})?$/'],
            'purchases.*.qty' => 'required|integer',
            'purchases.*.purchase_price' => ['nullable', 'regex:/^\d{1,10}(\.\d{1,2})?$/'],
            'purchases.*.provider_id' => 'nullable|integer',
            'purchases.*.label_id' => 'nullable|integer',
            'comment' => 'nullable|string',
            'address' => 'nullable|string',
            'delivery' => 'required|integer',
            'delivery_price' => ['nullable', 'regex:/^\d{1,10}(\.\d{1,2})?$/'],
            'note' => 'nullable|string',
        ];
    }
}
