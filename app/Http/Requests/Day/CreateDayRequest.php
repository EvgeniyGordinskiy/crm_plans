<?php

namespace App\Http\Requests\Day;

use Illuminate\Foundation\Http\FormRequest;

class CreateDayRequest extends FormRequest
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
            'id' => 'nullable|exists:days,id',
            'plan_id' => 'required|exists:plans,id',
            'day_name' => 'required|string|max:190',
        ];
    }
}
