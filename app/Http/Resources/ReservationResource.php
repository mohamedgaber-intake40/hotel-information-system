<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'room_id'          => $this->room_id,
            'hotel_id'         => $this->hotel_id,
            'hotel_name'       => $this->hotel_name,
            'city_name'        => $this->city_name,
            'country_iso_code' => $this->iso_code,
            'price_per_night'  => $this->price_per_night,
        ];
    }
}
