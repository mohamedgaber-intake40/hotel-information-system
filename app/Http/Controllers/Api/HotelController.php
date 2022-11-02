<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\HotelIndexRequest;
use App\Http\Requests\HotelStoreRequest;
use App\Http\Resources\HotelResource;
use App\Services\Api\Hotel\FetchHotelListService;
use App\Services\Api\Hotel\StoreHotelService;
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
}
