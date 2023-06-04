<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use mysql_xdevapi\Exception;

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
            'alias' => 'required|string|unique:categories,alias',
            'preview_image' => 'nullable',
            'parent_id' => 'nullable|integer',
            'is_active' => 'required|boolean',
        ];
    }
}
