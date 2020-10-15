<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'current_password' => 'sometimes|required',
            'new_password' => 'required|confirmed|min:6',
            'new_password_confirmation' => 'required|same:new_password'
        ];

        if ($this->path() === 'api/change-password') {
            $rules['new_password'] .= '|different:current_password';
        }

        return $rules;
    }

    /**
     * Translate fields with user friendly name.
     *
     * @return array
     */
    public function attributes(){
        return [
            'current_password' => 'Current password',
            'new_password' => 'New password',
            'new_password_confirmation' => 'Confirm new password',
        ];
    }
}
