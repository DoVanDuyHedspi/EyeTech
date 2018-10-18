<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Customer extends JsonResource
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
            '_id' => $this->_id,
            'owner_id' => $this->owner_id,
            'image_url_array' => $this->image_url_array,
            'vector' => $this->vector,
            'name' => $this->name,
            'age' => $this->age,
            'gender' => $this->gender,
            'telephone' => $this->telephone,
            'type' => $this->type,
            'address' => $this->address,
            'favorites' => $this->favorites,
            'note' => $this->note,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
