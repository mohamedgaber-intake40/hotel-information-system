<?php


namespace App\Services\City;


use App\Models\City;

class DeleteCityService
{
    public function execute(City $city)
    {
        return $city->deleteOrFail();
    }
}
