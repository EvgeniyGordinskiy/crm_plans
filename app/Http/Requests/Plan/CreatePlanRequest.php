<?php

namespace App\Http\Requests\Plan;

use Illuminate\Foundation\Http\FormRequest;

class CreatePlanRequest extends FormRequest
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
            'id' => 'nullable|exists:plans,id',
            'plan_name' => 'required|string|max:190',
            'plan_description' => 'required|string|max:190',
            'plan_difficulty' => 'required|in:1,2,3'
        ];
    }
}
