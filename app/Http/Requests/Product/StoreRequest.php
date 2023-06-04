<?php

namespace App\Http\Requests\Product;

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
            'part_number' => 'required|string',
            'name' => 'required|string',
            'alias' => 'required|string',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'price' => ['required', 'regex:/^\d{1,10}(\.\d{1,2})?$/'],
            'old_price' => ['nullable', 'regex:/^\d{1,10}(\.\d{1,2})?$/'],
            'qty' => 'required|integer',
            'purchase_price' => ['nullable', 'regex:/^\d{1,10}(\.\d{1,2})?$/'],
            'is_active' => 'required|boolean',
            'category_id' => 'required|integer',
            'brand_id' => 'required|integer',
        ];
    }
}
