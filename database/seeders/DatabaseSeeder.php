<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CountrySeeder::class);
        $this->call(FacilitySeeder::class);
        $this->call(UserSeeder::class);
    }
}
