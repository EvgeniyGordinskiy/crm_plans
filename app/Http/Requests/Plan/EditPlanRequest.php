<?php

namespace App\Http\Requests\Plan;

use Illuminate\Foundation\Http\FormRequest;

class EditPlanRequest extends FormRequest
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
            'id' => 'required|exists:plans,id',
            'plan_name' => 'nullable|string|max:190',
            'plan_description' => 'nullable|string|max:190',
            'plan_difficulty' => 'nullable|in:1,2,3'
        ];
    }
}
