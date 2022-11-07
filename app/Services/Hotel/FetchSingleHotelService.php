<?php


namespace App\Services\Hotel;


use App\Models\Hotel;

class FetchSingleHotelService
{
    public function execute(Hotel $hotel)
    {
        return $hotel->load('city.country','rooms.facilities');
    }
}
