<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomIndexRequest;
use App\Http\Requests\RoomStoreRequest;
use App\Http\Resources\RoomResource;
use App\Models\Hotel;
use App\Models\Room;
use App\Services\Api\Room\FetchRoomListService;
use App\Services\Api\Room\FetchSingleRoomService;
use App\Services\Api\Room\StoreRoomService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoomController extends Controller
{
    public function index(Hotel $hotel, RoomIndexRequest $request, FetchRoomListService $fetchRoomListService)
    {
        return apiResponse()->success()
                            ->data(RoomResource::collection(
                                $fetchRoomListService->execute(
                                    $hotel,
                                    (int)$request->per_page
                                )
                            ))
                            ->send();
    }

    public function store(Hotel $hotel, RoomStoreRequest $request, StoreRoomService $storeRoomService)
    {
        return apiResponse()->success()
                            ->data(RoomResource::make(
                                $storeRoomService->execute($hotel,$request->validated())
                            ))
                            ->message(__('rooms.stored.success'))
                            ->statusCode(Response::HTTP_CREATED)
                            ->send();
    }

    public function show(Hotel $hotel,$room,FetchSingleRoomService $fetchSingleRoomService)
    {
        return apiResponse()->success()
                            ->data(RoomResource::make(
                                $fetchSingleRoomService->execute($hotel,$room)
                            ))
                            ->send();
    }
}
