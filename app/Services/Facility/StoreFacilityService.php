<?php


namespace App\Services\Facility;


use App\Models\Facility;

class StoreFacilityService
{
    public function execute($data)
    {
        return Facility::create($data);
    }
}
