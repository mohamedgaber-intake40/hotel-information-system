<?php


namespace App\Services\Country;


use App\Models\Country;

class DeleteCountryService
{
    public function execute(Country $country)
    {
        return $country->deleteOrFail();
    }
}
