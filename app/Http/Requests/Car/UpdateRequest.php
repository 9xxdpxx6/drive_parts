<?php

namespace App\Http\Requests\Car;

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
            'vin_code' => 'nullable|string|unique:cars,vin_code,' . $this->car_id,
            'car_id' => 'required|integer|exists:cars,id',
            'car_model_id' => 'required|integer',
            'modification' => 'nullable|string',
            'year' => 'required|digits:4|integer|min:1900|max:'.(date('Y')+1),
            'user_id' => 'required|integer',
            'is_active' => 'required|boolean',
        ];
    }
}
