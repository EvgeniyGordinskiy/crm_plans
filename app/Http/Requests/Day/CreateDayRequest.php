<?php

namespace App\Http\Requests\Day;

use App\Http\Requests\BaseRequest;

class CreateDayRequest extends BaseRequest
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
            'day_name' => $this->sanitizeString('day_name'),
            'id' => $this->input('id'),
            'plan_id' => $this->input('plan_id'),
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
            'id' => 'nullable|exists:plan_days,id',
            'plan_id' => 'nullable|exists:plans,id',
            'day_name' => 'required|string|max:190',
        ];
    }
}
