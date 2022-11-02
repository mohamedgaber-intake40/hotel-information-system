<?php


namespace App\Services\Api\Hotel;


use App\Models\Hotel;

class FetchHotelListService
{
    const PER_PAGE = 10;

    public function execute($perPage = self::PER_PAGE)
    {
        return Hotel::query()->paginate($perPage);
    }
}
