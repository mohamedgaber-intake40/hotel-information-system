<?php


namespace App\Services\Room;


use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UpdateRoomService
{
    public function execute(Hotel $hotel,$roomId,$data)
    {
        try {
            DB::beginTransaction();
            $room = $hotel->rooms()->findOrFail($roomId);
            $room->update(Arr::only($data, [ 'number', 'price_per_night' ]));
            $room->facilities()->sync($data[ 'facilities' ]);
            DB::commit();
            return $room->load('facilities:id,name');
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
