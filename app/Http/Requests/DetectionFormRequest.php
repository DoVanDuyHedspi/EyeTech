<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DetectionFormRequest extends FormRequest
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
            'vector' => 'required',
            'customer_id' => 'required',
            'time_in' => 'required|date|date_format:Y-m-d H:i:s',
            'camera_id' => 'required',
            'image_camera_base64_array' => 'required',
        ];
    }
}
