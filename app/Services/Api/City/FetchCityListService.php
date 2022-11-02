<?php


namespace App\Services\Api\City;


use App\Models\City;

class FetchCityListService
{
    const PER_PAGE = 10;

    public function execute($filterData = [], $perPage = self::PER_PAGE)
    {
        return City::query()
                   ->filter($filterData)
                   ->paginate($perPage);
    }
}
