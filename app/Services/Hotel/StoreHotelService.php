<?php


namespace App\Services\Hotel;


use App\Models\Hotel;

class StoreHotelService
{
    public function execute($data)
    {
        return Hotel::create($data);
    }
}
