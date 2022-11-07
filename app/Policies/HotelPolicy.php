<?php

namespace App\Policies;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HotelPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Hotel  $hotel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Hotel $hotel)
    {
        if ($hotel->rooms()->exists())
            return $this->deny(__('hotel.authorize.cant_delete'));
        return true;
    }


}
