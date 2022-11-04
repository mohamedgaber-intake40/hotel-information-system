<?php


namespace App\Services\Facility;


use App\Models\Facility;

class FetchFacilityListService
{
    const PER_PAGE = 10;

    public function execute($perPage = self::PER_PAGE)
    {
        return Facility::query()->paginate($perPage);
    }
}
