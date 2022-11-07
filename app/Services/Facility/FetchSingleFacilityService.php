<?php


namespace App\Services\Facility;


use App\Models\Facility;

class FetchSingleFacilityService
{
    public function execute(Facility $facility)
    {
        return $facility;
    }
}
