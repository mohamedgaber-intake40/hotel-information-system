<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Room\RoomIndexRequest;
use App\Http\Requests\Room\RoomStoreRequest;
use App\Http\Requests\Room\RoomUpdateRequest;
use App\Http\Resources\RoomResource;
use App\Models\Hotel;
use App\Models\Room;
use App\Services\Room\DeleteRoomService;
use App\Services\Room\FetchRoomListService;
use App\Services\Room\FetchSingleRoomService;
use App\Services\Room\StoreRoomService;
use App\Services\Room\UpdateRoomService;
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
                                $storeRoomService->execute($hotel, $request->validated())
                            ))
                            ->message(__('rooms.stored.success'))
                            ->statusCode(Response::HTTP_CREATED)
                            ->send();
    }

    public function show(Hotel $hotel, $room, FetchSingleRoomService $fetchSingleRoomService)
    {
        return apiResponse()->success()
                            ->data(RoomResource::make(
                                $fetchSingleRoomService->execute($hotel, $room)
                            ))
                            ->send();
    }

    public function update(Hotel $hotel, $room, RoomUpdateRequest $request, UpdateRoomService $updateRoomService)
    {
        return apiResponse()->success()
                            ->data(RoomResource::make(
                                $updateRoomService->execute($hotel, $room, $request->validated())
                            ))
                            ->message(__('rooms.success.updated'))
                            ->send();
    }

    public function destroy(Hotel $hotel, $room, DeleteRoomService $deleteRoomService)
    {
        $room = $hotel->rooms()->findOrFail($room);
        $this->authorize('delete',$room);
        $deleteRoomService->execute($room);
        return apiResponse()->success()
                            ->message(__('rooms.success.deleted'))
                            ->send();
    }
}
