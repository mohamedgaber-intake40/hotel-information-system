<?php


namespace App\Services\Facility;


use App\Models\Facility;

class UpdateFacilityService
{
    public function execute(Facility $facility, $data)
    {
        $facility->update($data);
        return $facility;
    }

}
