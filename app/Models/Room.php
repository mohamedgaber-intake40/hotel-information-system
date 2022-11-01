<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    /*
   |--------------------------------------------------------------------------|
   |                              Arrays                                      |
   |--------------------------------------------------------------------------|
   */
    protected $fillable = [
        'number',
        'hotel_id'
    ];
    /*
     |--------------------------------------------------------------------------|
     |                           Constants                                      |
     |--------------------------------------------------------------------------|
     */

    /*
    |--------------------------------------------------------------------------|
    |                             Mutators                                     |
    |--------------------------------------------------------------------------|
    */

    /*
    |--------------------------------------------------------------------------|
    |                            Accessors                                     |
    |--------------------------------------------------------------------------|
    */

    /*
    |--------------------------------------------------------------------------|
    |                             Helpers                                      |
    |--------------------------------------------------------------------------|
    */


    /*
    |--------------------------------------------------------------------------|
    |                              Scopes                                      |
    |--------------------------------------------------------------------------|
   */

    /*
    |--------------------------------------------------------------------------|
    |                              Relations                                   |
    |--------------------------------------------------------------------------|
   */

    public function facilities()
    {
        return $this->belongsToMany(Facility::class)->withTimestamps()->using(RoomFacility::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
