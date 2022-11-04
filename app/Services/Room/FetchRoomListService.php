<?php


namespace App\Services\Room;


use App\Models\Hotel;
use App\Models\Room;

class FetchRoomListService
{
    const PER_PAGE = 10;

    public function execute(Hotel $hotel,$perPage = self::PER_PAGE)
    {
        return $hotel->rooms()
                   ->with([
                              'hotel:id,name,city_id',
                              'hotel.city:id,name,country_id',
                              'hotel.city.country:id,name,iso_code',
                              'facilities:id,name'
                          ])
                   ->paginate($perPage);
    }
}
