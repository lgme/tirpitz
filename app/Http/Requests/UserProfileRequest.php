<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'sometimes',
            'last_name' => 'sometimes',
            'image_url' => 'sometimes|required',
            'phone' => 'sometimes|required',
        ];
    }

    /**
     * Translate fields with user friendly name.
     *
     * @return array
     */
    public function attributes(){
        return  [
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'image_url' => 'Profile image',
            'phone' => 'Phone number',
        ];
    }
}
