<?php


namespace App\Services\City;


use App\Models\City;

class FetchSingleCityService
{
    public function execute(City $city)
    {
        return $city->load('country');
    }
}
