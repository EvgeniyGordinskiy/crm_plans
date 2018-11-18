<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends BaseRequest
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
            'user_name' => $this->sanitizeString('user_name'),
            'id' => $this->input('id'),
            'user_email' => $this->input('user_email'),
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
            'id' => 'required|exists:users,id',
            'user_name' => 'nullable|string|max:190',
            'user_email' => 'nullable|email',
        ];
    }
}
