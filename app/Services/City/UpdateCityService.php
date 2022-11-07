<?php


namespace App\Services\City;


use App\Models\City;

class UpdateCityService
{
    public function execute(City $city, $data)
    {
        $city->update($data);
        return $city->load('country');
    }

}
