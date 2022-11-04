<?php

namespace App\Http\Controllers;

use App\Filters\ReservationFilter;
use App\Http\Requests\Reservation\ReservationIndexRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Room;
use App\Services\Api\Reservation\FetchReservationListService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __invoke(ReservationIndexRequest $request, FetchReservationListService $fetchReservationListService)
    {
//        $query = Room::query()
//                     ->join('hotels', 'rooms.hotel_id', 'hotels.id')
//                     ->join('cities', 'hotels.city_id', 'cities.id')
//                     ->join('countries', 'cities.country_id', 'countries.id')
//                     ->select([ 'rooms.id', 'number', 'price_per_night', 'hotels.name as hotel_name', 'cities.name as city_name','countries.name as country_name', 'iso_code', 'city_id', 'hotel_id', 'country_id' ])
//                     ->orderBy($request->sort_by,$request->get('sort_direction','asc'));
//
//        return ReservationFilter::create($query, ))->get();
        $data = ReservationResource::collection(
            $fetchReservationListService->execute(
                $request->only([ 'search', 'city_id', 'hotel_id', 'country_id' ]),
                $request->sort_by,
                $request->sort_direction,
                $request->per_page
            )
        );
        return apiResponse()->success()
                            ->data($data)
                            ->send();
    }
}
