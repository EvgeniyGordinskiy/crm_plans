<?php

namespace App\Http\Requests\Part;

use Illuminate\Foundation\Http\FormRequest;

class ChangeOrderRequest extends FormRequest
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
            'item_id' => 'required',
            'after_item_id' => 'nullable',
            'before_item_id' => 'nullable',
//            'exercise_id' => 'nullable|exists:exercises,id',
            'parent_id' => 'required',
            'resource' => 'required|string',
        ];

    }

}
