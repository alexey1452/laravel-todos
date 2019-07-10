<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'first_name' => [
                'sometimes',
                'string'
            ],
            'last_name' => [
                'sometimes',
                'string'
            ],
            'email' => [
                'sometimes',
                'string',
                'email',
                'min:6',
            ],
            'password'   => [
                'sometimes',
                'string',
                'min:6',
            ],
            'avatar_id' => [
                'sometimes',
                'integer',
                'nullable',
                'exists:files,id',
            ],
        ];
    }
}
