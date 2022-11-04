<?php


namespace App\Services\Room;


use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class StoreRoomService
{
    public function execute(Hotel $hotel,$data)
    {
        try {
            DB::beginTransaction();
            $room = $hotel->rooms()->create(Arr::only($data, [ 'number', 'price_per_night' ]));
            $room->facilities()->attach($data[ 'facilities' ]);
            DB::commit();
            return $room->load('facilities:id,name');
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
