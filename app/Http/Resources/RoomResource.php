<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
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
            'id'              => $this->id,
            'number'          => $this->number,
            'price_per_night' => $this->price_per_night,
            'hotel'           => HotelResource::make($this->whenLoaded('hotel')),
            'facilities'      => FacilityResource::collection($this->whenLoaded('facilities'))
        ];
    }
}
