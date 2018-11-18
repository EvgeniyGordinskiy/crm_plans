<?php

namespace App\Http\Requests\Day;

use Illuminate\Foundation\Http\FormRequest;

class AddExerciseRequest extends FormRequest
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
            'day_id' => 'required|exists:plan_days,id',
            'exercise_id' => 'required|exists:exercises,id',
        ];
    }
}
