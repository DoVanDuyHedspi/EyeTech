<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventFormRequest extends FormRequest
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
            'customer_id' => 'required',
            'store_id' => 'required',
            'camera_id' => 'required',
            'vector' => 'required',
            'time_in' => 'required|date|date_format:Y-m-d H:i:s',
            'image_camera_url_array' => 'required',
            'image_detection_url_array' => 'required',
        ];
    }
}
