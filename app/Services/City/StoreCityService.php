<?php


namespace App\Services\City;


use App\Models\City;

class StoreCityService
{
    public function execute($data)
    {
        return City::create($data)->load('country');
    }
}
