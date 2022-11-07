<?php

namespace App\Policies;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FacilityPolicy
{
    use HandlesAuthorization;



    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Facility  $facility
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Facility $facility)
    {
        if ($facility->rooms()->exists())
            return $this->deny('facility.authorize.cant_delete');
        return true;
    }


}
