<?php


namespace App\Services\Api\Country;


use App\Models\Country;

class FetchCountryListService
{
    const PER_PAGE = 10;

    public function execute($perPage = self::PER_PAGE)
    {
        return Country::query()->paginate($perPage);
    }
}
