<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BikeReservationResource extends JsonResource
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
            'bike' => $this->bike->name,
            'employee' => $this->employee->getFullname(),
            'reserved_from' => date('d.M Y h:i', strtotime($this->reserved_from)),
            'reserved_to' => date('d.M Y h:i', strtotime($this->reserved_to)),
        ];
    }
}
