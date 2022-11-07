<?php

namespace App\Providers;

use App\Models\City;
use App\Models\Country;
use App\Models\Facility;
use App\Models\Hotel;
use App\Models\Room;
use App\Policies\CityPolicy;
use App\Policies\CountryPolicy;
use App\Policies\FacilityPolicy;
use App\Policies\HotelPolicy;
use App\Policies\RoomPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        City::class => CityPolicy::class,
        Country::class => CountryPolicy::class,
        Facility::class => FacilityPolicy::class,
        Room::class => RoomPolicy::class,
        Hotel::class => HotelPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
