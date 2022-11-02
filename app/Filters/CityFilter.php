<?php


namespace App\Filters;


use Filter\Filter;

class CityFilter extends Filter
{
    public function filterCountry($country)
    {
        return $this->query->where('country_id',$country);
    }

}
