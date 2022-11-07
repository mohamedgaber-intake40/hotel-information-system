<?php


namespace App\Services\Country;


use App\Models\Country;

class UpdateCountryService
{
    public function execute(Country $country, $data)
    {
        $country->update($data);
        return $country->load('cities');
    }

}
