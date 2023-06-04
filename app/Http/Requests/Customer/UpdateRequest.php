<?php

namespace App\Http\Requests\Customer;

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
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email,' . $this->customer_id,
            'customer_id' => 'required|integer|exists:users,id',
            'phone_number' => 'required|string|unique:users,phone_number,' . $this->customer_id,
            'group_id' => 'nullable|integer',
            'cars' => 'nullable|array',
            'info' => 'nullable|string',
            'is_active' => 'required|boolean',
        ];
    }
}
