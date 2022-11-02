<?php


namespace App\Services\Api\Hotel;


use App\Models\Hotel;

class FetchHotelListService
{
    const PER_PAGE = 10;

    public function execute($perPage = self::PER_PAGE)
    {
        return Hotel::query()
                    ->with([
                               'city:id,name,country_id',
                               'city.country:id,name,iso_code',
                           ])
                    ->paginate($perPage);
    }
}
