<?php


namespace App\Services\Room;


use App\Models\Hotel;

class DeleteRoomService
{
    public function execute(Hotel $hotel,$roomId)
    {
        return $hotel->rooms()->findOrFail($roomId)->deleteOrFail();
    }
}
