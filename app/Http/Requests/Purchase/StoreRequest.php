<?php

namespace App\Http\Requests\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'product_id' => 'required|integer',
            'price' => ['required', 'regex:/^\d{1,10}(\.\d{1,2})?$/'],
            'qty' => 'required|integer',
            'purchase_price' => ['nullable', 'regex:/^\d{1,10}(\.\d{1,2})?$/'],
            'provider_id' => 'nullable|integer',
            'label_id' => 'nullable|integer',
        ];
    }
}
