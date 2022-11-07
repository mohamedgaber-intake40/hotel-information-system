<?php


namespace App\Services\Hotel;


use App\Models\Hotel;

class UpdateHotelService
{
    public function execute(Hotel $hotel, $data)
    {
        $hotel->update($data);
        return $hotel->load([
                                'city.country',
                                'rooms.facilities:id,name'
                            ]);
    }
}
