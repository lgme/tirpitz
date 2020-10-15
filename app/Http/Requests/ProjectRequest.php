<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            'client_name' => 'sometimes',
            'start_date' => 'sometimes',
            'end_date' => 'sometimes',
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
            'name' => 'Project name',
            'client_name' => 'Client name',
            'start_date' => 'Project start date',
            'end_date' => 'Project end date',
            'description' => 'Description',
        ];
    }
}
