<?php


namespace Tests\Feature;


use App\Models\City;
use App\Models\Country;
use App\Models\Facility;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RoomTest extends TestCase
{
    use RefreshDatabase;

    public function testCantFetchRoomsWithoutAuthenticated()
    {
        $this->prepareData();
        $hotel    = Hotel::first();
        $response = $this->getJson("api/hotels/$hotel->id/rooms");
        $response->assertStatus(401);
    }

    public function testCanFetchRoomsWithAuthenticated()
    {
        $this->prepareData();
        $hotel    = Hotel::first();
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->getJson("api/hotels/$hotel->id/rooms");
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->hasAll('data', 'links', 'meta', 'message'));
    }


    public function testCanLimitRoomsWithPerPageAsQueryString()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $hotel    = Hotel::first();
        $response = $this->actingAs($user)->getJson("api/hotels/$hotel->id/rooms?per_page=1");
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) =>
        $json->hasAll('data', 'links', 'meta', 'message')
             ->where('meta.per_page',1)
        );
    }

    public function testCantLimitRoomsWithPerPageBy0AsQueryString()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $hotel    = Hotel::first();
        $response = $this->actingAs($user)->getJson("api/hotels/$hotel->id/rooms?per_page=0");
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('per_page');
    }

    public function testCantLimitRoomsWithPerPageByStringValueAsQueryString()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $hotel    = Hotel::first();
        $response = $this->actingAs($user)->getJson("api/hotels/$hotel->id/rooms?per_page=stringValue");
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('per_page');
    }


    public function testCantLimitRoomsWithPerPageBy51AsQueryString()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $hotel    = Hotel::first();
        $response = $this->actingAs($user)->getJson("api/hotels/$hotel->id/rooms?per_page=51");
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('per_page');
    }

    public function testCantShowRoomsWithoutAuthenticated()
    {
        $this->prepareData();
        $hotel    = Hotel::first();
        $room     = Room::first();
        $response = $this->getJson("api/hotels/$hotel->id/rooms/$room->id");
        $response->assertStatus(401);
    }


    public function testCanShowRoomsAsAuthenticated()
    {
        $this->prepareData();
        $hotel    = Hotel::first();
        $room     = Room::first();
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->getJson("api/hotels/$hotel->id/rooms/$room->id");
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->hasAll('data', 'data.facilities','data.hotel','data.hotel.city','data.hotel.city.country', 'message'));
    }

    public function testCantStoreRoomWithoutAuthenticated()
    {
        $this->prepareData();
        $hotel    = Hotel::first();
        $response = $this->postJson("api/hotels/$hotel->id/rooms");
        $response->assertStatus(401);
    }

    public function testRequiredValidationForNumber()
    {
        $this->prepareData();
        $hotel    = Hotel::first();
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->postJson("api/hotels/$hotel->id/rooms",[
            'price_per_night' => random_int(100,200) / 10,
            'facilities' => Facility::query()->inRandomOrder()->take(3)->pluck('id')->toArray()
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('number');

    }

    public function testIntegerValidationForNumber()
    {
        $this->prepareData();
        $hotel    = Hotel::first();
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->postJson("api/hotels/$hotel->id/rooms",[
            'number' => Str::random(4),
            'price_per_night' => random_int(100,200) / 10,
            'facilities' => Facility::query()->inRandomOrder()->take(3)->pluck('id')->toArray()
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('number');

    }

    public function testMinValidationForNumber()
    {
        $this->prepareData();
        $hotel    = Hotel::first();
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->postJson("api/hotels/$hotel->id/rooms",[
            'number' => 0,
            'price_per_night' => random_int(100,200) / 10,
            'facilities' => Facility::query()->inRandomOrder()->take(3)->pluck('id')->toArray()
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('number');

    }

    public function testMaxValidationForNumber()
    {
        $this->prepareData();
        $hotel    = Hotel::first();
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->postJson("api/hotels/$hotel->id/rooms",[
            'number' => 100000,
            'price_per_night' => random_int(100,200) / 10,
            'facilities' => Facility::query()->inRandomOrder()->take(3)->pluck('id')->toArray()
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('number');

    }


    public function testUniqueValidationForNumber()
    {
        $this->prepareData();
        $hotel    = Hotel::first();
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->postJson("api/hotels/$hotel->id/rooms",[
            'number' => Room::first()->number,
            'price_per_night' => random_int(100,200) / 10,
            'facilities' => Facility::query()->inRandomOrder()->take(3)->pluck('id')->toArray()
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('number');

    }

    public function testRequiredValidationForPricePerNight()
    {
        $this->prepareData();
        $hotel    = Hotel::first();
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->postJson("api/hotels/$hotel->id/rooms",[
            'number' => random_int(1,100) ,
            'facilities' => Facility::query()->inRandomOrder()->take(3)->pluck('id')->toArray()
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('price_per_night');
    }

    public function testNumericValidationForPricePerNight()
    {
        $this->prepareData();
        $hotel    = Hotel::first();
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->postJson("api/hotels/$hotel->id/rooms",[
            'number' => random_int(1,100) ,
            'price_per_night' => Str::random(10),
            'facilities' => Facility::query()->inRandomOrder()->take(3)->pluck('id')->toArray()
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('price_per_night');
    }

    public function testMinValidationForPricePerNight()
    {
        $this->prepareData();
        $hotel    = Hotel::first();
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->postJson("api/hotels/$hotel->id/rooms",[
            'number' => random_int(1,100) ,
            'price_per_night' => 0.9,
            'facilities' => Facility::query()->inRandomOrder()->take(3)->pluck('id')->toArray()
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('price_per_night');
    }
    public function testMaxValidationForPricePerNight()
    {
        $this->prepareData();
        $hotel    = Hotel::first();
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->postJson("api/hotels/$hotel->id/rooms",[
            'number' => random_int(1,100) ,
            'price_per_night' => 99999.1,
            'facilities' => Facility::query()->inRandomOrder()->take(3)->pluck('id')->toArray()
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('price_per_night');
    }

    public function testRequiredValidationForFacilities()
    {
        $this->prepareData();
        $hotel    = Hotel::first();
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->postJson("api/hotels/$hotel->id/rooms",[
            'number' => random_int(1,100) ,
            'price_per_night' => 100,
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('facilities');
    }


    public function testArrayValidationForFacilities()
    {
        $this->prepareData();
        $hotel    = Hotel::first();
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->postJson("api/hotels/$hotel->id/rooms",[
            'number' => random_int(1,100) ,
            'price_per_night' => 100,
            'facilities' => Str::random(2),
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('facilities');
    }

    public function testRequiredValidationForFacilitiesArrayValues()
    {
        $this->prepareData();
        $hotel    = Hotel::first();
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->postJson("api/hotels/$hotel->id/rooms",[
            'number' => random_int(1,100) ,
            'price_per_night' => 100,
            'facilities' => [null,''],
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('facilities.0');
        $response->assertJsonValidationErrorFor('facilities.1');
    }

    public function testExistsValidationForFacilitiesArrayValues()
    {
        $this->prepareData();
        $hotel    = Hotel::first();
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->postJson("api/hotels/$hotel->id/rooms",[
            'number' => random_int(1,100) ,
            'price_per_night' => 100,
            'facilities' => [0,Facility::max('id') + 1 ],
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('facilities.0');
        $response->assertJsonValidationErrorFor('facilities.1');
    }

    public function testDistinctValidationForFacilitiesArrayValues()
    {
        $this->prepareData();
        $hotel    = Hotel::first();
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->postJson("api/hotels/$hotel->id/rooms",[
            'number' => random_int(1,100) ,
            'price_per_night' => 100,
            'facilities' => [Facility::first()->id,Facility::first()->id],
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('facilities.0');
        $response->assertJsonValidationErrorFor('facilities.1');
    }



    public function testCanStoreRoomAsAuthenticated()
    {
        $this->prepareData();
        $hotel    = Hotel::first();
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->postJson("api/hotels/$hotel->id/rooms",[
            'number' => random_int(1,1000),
            'price_per_night' => random_int(100,200) / 10,
            'facilities' => Facility::query()->inRandomOrder()->take(3)->pluck('id')->toArray()
        ]);
        $response->assertStatus(201);

    }

    private function prepareData()
    {
        Country::factory()
               ->has(City::factory()
                         ->has(Hotel::factory()
                                    ->has(Room::factory()->count(1))
                                    ->count(1))
                         ->count(1))
               ->count(1)
               ->create();

        Facility::factory()->count(5)->create();
    }
}
