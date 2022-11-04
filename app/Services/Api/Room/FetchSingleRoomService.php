<?php


namespace App\Services\Api\Room;


class FetchSingleRoomService
{
    public function execute($hotel, $roomId)
    {
        return $hotel->rooms()
                     ->with(
                         [
                             'hotel:id,name,city_id',
                             'hotel.city:id,name,country_id',
                             'hotel.city.country:id,name,iso_code',
                             'facilities:id,name,description'
                         ]
                     )
                     ->findOrFail($roomId);
    }
}
