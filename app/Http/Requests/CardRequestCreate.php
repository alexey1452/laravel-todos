<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardRequestCreate extends FormRequest
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
            'user_id' => 'required|integer',
            'name' => 'bail|required|max:255',
            'description' => 'bail|sometimes|max:500',
            'image_id' => 'sometimes|integer|max:500',
        ];
    }
}
