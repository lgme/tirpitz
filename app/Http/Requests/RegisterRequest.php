<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'first_name' => 'sometimes',
            'last_name' => 'sometimes',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
            'image_url' => 'sometimes|required',
            'phone' => 'sometimes|required',
        ];

        if (config('config.terms_and_conditions')) {
            $rules['tnc'] = 'sometimes|accepted';
        }

        return $rules;
    }

    /**
     * Translate fields with user friendly name.
     *
     * @return array
     */
    public function attributes(){
        $attributes = [
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => 'Email',
            'password' => 'Password',
            'password_confirmation' => 'Confirm password',
            'image_url' => 'Profile image',
            'phone' => 'Phone number',
        ];

        if (config('config.terms_and_conditions')) {
            $attributes['tnc'] = 'Terms & Conditions';
        }

        return $attributes;
    }
}
