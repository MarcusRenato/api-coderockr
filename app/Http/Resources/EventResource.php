<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'date' => $this->date,
            'hour' => $this->hour,
            'place' => $this->place,
            '_links' => [
                'detail' => route('event.single', $this->id)
            ]
        ];
    }
}
