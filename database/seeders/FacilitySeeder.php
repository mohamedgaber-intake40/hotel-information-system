<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\Room;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Facility::factory()->count(20)->create()->each(function ($facility){
            $facility->rooms()->attach(Room::query()->inRandomOrder()->take(200)->pluck('id'));
        });
    }
}
