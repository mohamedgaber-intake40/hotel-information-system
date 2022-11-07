<?php


namespace App\Services\Room;


use App\Models\Hotel;
use App\Models\Room;

class DeleteRoomService
{
    public function execute(Room $room)
    {
        return $room->deleteOrFail();
    }
}
