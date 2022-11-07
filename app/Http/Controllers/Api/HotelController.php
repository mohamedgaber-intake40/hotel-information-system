<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hotel\HotelIndexRequest;
use App\Http\Requests\Hotel\HotelStoreRequest;
use App\Http\Requests\Hotel\HotelUpdateRequest;
use App\Http\Resources\HotelResource;
use App\Models\Hotel;
use App\Services\Hotel\DeleteHotelService;
use App\Services\Hotel\FetchHotelListService;
use App\Services\Hotel\FetchSingleHotelService;
use App\Services\Hotel\StoreHotelService;
use App\Services\Hotel\UpdateHotelService;
use Symfony\Component\HttpFoundation\Response;

class HotelController extends Controller
{
    public function index(HotelIndexRequest $request,FetchHotelListService $fetchHotelListService)
    {
        return apiResponse()->success()
                            ->data(HotelResource::collection(
                                $fetchHotelListService->execute(
                                    (int) $request->per_page
                                )
                            ))
                            ->send();
    }

    public function show(Hotel $hotel,FetchSingleHotelService $fetchSingleHotelService)
    {
        return apiResponse()->success()
                            ->data(HotelResource::make(
                                $fetchSingleHotelService->execute(
                                    $hotel
                                )
                            ))
                            ->send();
    }


    public function store(HotelStoreRequest $request, StoreHotelService $storeHotelService)
    {
        return apiResponse()->success()
                            ->data(HotelResource::make(
                                $storeHotelService->execute($request->validated())
                            ))
                            ->message(__('hotels.stored.success'))
                            ->statusCode(Response::HTTP_CREATED)
                            ->send();
    }

    public function update(Hotel $hotel,HotelUpdateRequest $request,UpdateHotelService $updateHotelService)
    {
        return apiResponse()->success()
                            ->data(HotelResource::make(
                                $updateHotelService->execute($hotel,$request->validated())
                            ))
                            ->message(__('hotels.updated.success'))
                            ->statusCode(Response::HTTP_CREATED)
                            ->send();
    }

    public function destroy(Hotel $hotel,DeleteHotelService $deleteHotelService)
    {
        $this->authorize('delete',$hotel);
        $deleteHotelService->execute($hotel);
        return apiResponse()->success()
                            ->message(__('hotels.success.deleted'))
                            ->send();
    }
}
