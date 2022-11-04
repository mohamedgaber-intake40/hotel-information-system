<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::factory()
               ->has(City::factory()
                         ->has(Hotel::factory()
                                    ->has(Room::factory()->count(10))
                                    ->count(5))
                         ->count(5))
               ->count(5)
               ->create();
    }
}
