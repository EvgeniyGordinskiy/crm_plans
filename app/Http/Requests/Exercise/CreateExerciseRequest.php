<?php

namespace App\Http\Requests\Exercise;

use App\Http\Requests\BaseRequest;

class CreateExerciseRequest extends BaseRequest
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

    protected function prepareForValidation()
    {
        $this->replace([
            'exercise_name' => $this->sanitizeString('exercise_name'),
            'id' => $this->input('id'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'nullable|exists:exercises,id',
//            'plan_id' => 'required|exists:plans,id',
            'exercise_name' => 'required|string|max:190',
        ];
    }
}
