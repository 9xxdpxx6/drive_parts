<?php

namespace App\Http\Requests\CarModel;

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
            'alias' => 'required|string|unique:brands,alias,' . $this->car_model_id,
            'car_model_id' => 'required|integer|exists:car_models,id',
            'brand_id' => 'required|integer',
            'is_active' => 'required|boolean',
        ];
    }
}
