<?php

namespace App\Http\Controllers;

use App\Filters\ReservationFilter;
use App\Http\Requests\Reservation\ReservationIndexRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Hotel;
use App\Models\Room;
use App\Services\Reservation\FetchReservationListService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __invoke(ReservationIndexRequest $request, FetchReservationListService $fetchReservationListService)
    {
        $data = ReservationResource::collection(
            $fetchReservationListService->execute(
                $request->only([ 'search', 'city_id', 'hotel_id', 'country_id' ]),
                $request->sort_by,
                $request->sort_direction,
                (int) $request->per_page
            )
        );
        return apiResponse()->success()
                            ->data($data)
                            ->send();
    }
}
