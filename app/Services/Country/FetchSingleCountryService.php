<?php


namespace App\Services\Country;


use App\Models\Country;

class FetchSingleCountryService
{
    public function execute(Country $country)
    {
        return $country->load('cities');
    }
}
