<?php


namespace App\Services\Facility;


use App\Models\City;
use App\Models\Facility;

class DeleteFacilityService
{
    public function execute(Facility $facility)
    {
        return $facility->deleteOrFail();
    }
}
