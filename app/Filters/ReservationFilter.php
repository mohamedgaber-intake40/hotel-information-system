<?php


namespace App\Filters;


use Filter\Filter;

class ReservationFilter extends Filter
{
    public function filterSearch($search)
    {
        $this->query->where(function ($q) use ($search) {
            return $q->where('hotels.name','like',"%$search%")
                     ->orWhere('iso_code','like',"%$search%")
                     ->orWhere('cities.name','like',"%$search%")
                     ->orWhere('price_per_night',"$search");
        });
    }

    public function filterHotelId($hotelId)
    {
        return $this->query->where('hotel_id',$hotelId);
    }

    public function filterCityId($cityId)
    {
        return $this->query->where('city_id',$cityId);
    }

    public function filterCountryId($countryId)
    {
        return $this->query->where('country_id',$countryId);
    }
}
