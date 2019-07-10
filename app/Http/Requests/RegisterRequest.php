<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'password' => 'bail|required|min:6',
            'email' => 'bail|required|email|max:255',
            'first_name' => 'bail|required|max:255',
            'last_name' => 'bail|required|max:255',
        ];
    }
}
