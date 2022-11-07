<?php


namespace App\Services\Country;


use App\Models\Country;

class StoreCountryService
{
    public function execute($data)
    {
        return Country::create($data);
    }
}
