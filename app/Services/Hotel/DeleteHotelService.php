<?php


namespace App\Services\Hotel;


use App\Models\Hotel;

class DeleteHotelService
{
    public function execute(Hotel $hotel)
    {
        return $hotel->deleteOrFail();
    }
}
