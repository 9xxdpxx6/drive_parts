<?php

namespace App\Http\Requests\Group;

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
            'discount' => 'required|numeric|between:0,99.99',
            'alias' => 'required|string|unique:groups,alias,' . $this->group_id,
            'group_id' => 'required|integer|exists:groups,id',
            'is_active' => 'required|boolean',
        ];
    }
}
