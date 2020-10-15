<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeviceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'sometimes',
            'serial_number' => 'sometimes',
            'status' => 'sometimes',
            'type' => 'sometimes',
            'description' => 'sometimes',
        ];
    }

    /**
     * Translate fields with user friendly name.
     *
     * @return array
     */
    public function attributes(){
        return  [
            'name' => 'Device name',
            'serial_number' => 'Serial number',
            'status' => 'Status',
            'description' => 'Description',
        ];
    }
}
