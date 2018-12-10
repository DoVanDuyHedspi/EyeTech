<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerFormRequest extends FormRequest
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
            //
        ];
    }

    public function setRules()
    {
        return [
            'name' => 'required',
            'email' => 'required',
            'age' => 'required',
            'gender' => 'required',
            'type' => 'required',
            'telephone' => 'required',
            'country' => 'required',
            'city' => 'required',
            'location' => 'required',
            'favorites' => 'required',
            'note' => 'required',
        ];
    }
}
