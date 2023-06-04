<?php

namespace App\Http\Requests\API\Product;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
            'keyword' => 'nullable|string',
            'categories' => 'nullable|array',
            'brands' => 'nullable|array',
            'active' => 'nullable|boolean',
            'prices' => 'nullable|array',
            'sort' => 'nullable|string',
            'page' => 'required|integer',
            'per_page' => 'required|integer',
        ];
    }

}
