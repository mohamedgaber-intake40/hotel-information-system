<?php


namespace App\Services\Api\Hotel;


use App\Models\Hotel;

class StoreHotelService
{
    public function execute($data)
    {
        return Hotel::create($data);
    }
}
