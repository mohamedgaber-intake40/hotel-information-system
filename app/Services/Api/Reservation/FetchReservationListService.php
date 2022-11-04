<?php


namespace App\Services\Api\Reservation;


use App\Filters\ReservationFilter;
use App\Models\Room;

class FetchReservationListService
{
    const PER_PAGE = 10;

    public function execute($filterData = [], $sortBy = 'id', $sortDirection = 'asc', $perPage = self::PER_PAGE)
    {
        $query = Room::query()
                     ->join('hotels', 'rooms.hotel_id', 'hotels.id')
                     ->join('cities', 'hotels.city_id', 'cities.id')
                     ->join('countries', 'cities.country_id', 'countries.id')
                     ->select([
                                  'rooms.id',
                                  'number',
                                  'price_per_night',
                                  'hotels.name as hotel_name',
                                  'cities.name as city_name',
                                  'countries.name as country_name',
                                  'iso_code',
                                  'city_id',
                                  'hotel_id',
                                  'country_id'
                              ])
                     ->orderBy($sortBy, $sortDirection);

        return ReservationFilter::create($query, $filterData)
                                ->paginate($perPage);
    }
}
