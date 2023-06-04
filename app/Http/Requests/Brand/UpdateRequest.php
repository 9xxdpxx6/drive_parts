<?php

namespace App\Http\Requests\Brand;

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
            'alias' => 'required|string|unique:brands,alias,' . $this->brand_id,
            'brand_id' => 'required|integer|exists:brands,id',
            'preview_image' => 'nullable',
            'has_vehicles' => 'required|string',
            'image_for_delete' => 'nullable',
            'is_active' => 'required|boolean',
        ];
    }
}
