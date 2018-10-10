<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Event extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            '_id' => $this->id,
            'customer_id' => $this->customer_id,
            'vector' => $this->vector,
            'time_in' => $this->time_in,
            'camera_id' => $this->camera_id,
            'image_camera_url_array' => $this->image_camera_url_array,
            'image_detection_url_array' => $this->image_detection_url_array,
            'emotion' => $this->emotion,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
