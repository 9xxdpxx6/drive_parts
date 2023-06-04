<?php

namespace App\Http\Requests\Brand;

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
            'name' => 'required|string',
            'alias' => 'required|string|unique:brands,alias',
            'preview_image' => 'nullable',
            'has_vehicles' => 'required|boolean',
            'is_active' => 'required|boolean',
        ];
    }
}
