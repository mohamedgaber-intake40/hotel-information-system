<?php


namespace Tests\Feature;


use App\Models\City;
use App\Models\Country;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class HotelTest extends TestCase
{
    use RefreshDatabase;

    public function testCantFetchHotelsWithoutAuthenticated()
    {
        $response = $this->getJson('api/hotels');
        $response->assertStatus(401);
    }

    public function testCanFetchHotelsWithAuthenticated()
    {
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->getJson('api/hotels');
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->hasAll('data', 'links', 'meta', 'message')
        );
    }

    public function testCanLimitHotelsWithPerPageAsQueryString()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $response = $this->actingAs($user)->getJson("api/hotels?per_page=1");
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) =>
        $json->hasAll('data', 'links', 'meta', 'message')
             ->where('meta.per_page',1)
        );
    }

    public function testCantLimitHotelsWithPerPageBy0AsQueryString()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $response = $this->actingAs($user)->getJson("api/hotels?per_page=0");
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('per_page');
    }

    public function testCantLimitHotelsWithPerPageByStringValueAsQueryString()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $response = $this->actingAs($user)->getJson("api/hotels?per_page=stringValue");
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('per_page');
    }


    public function testCantLimitHotelsWithPerPageBy51AsQueryString()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $response = $this->actingAs($user)->getJson("api/hotels?per_page=51");
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('per_page');
    }


    public function testCantStoreHotelWithoutAuthenticated()
    {
        $response = $this->postJson('api/hotels');
        $response->assertStatus(401);
    }


    public function testRequiredValidationForName()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $response = $this->actingAs($user)->postJson('/api/hotels', [
            'city_id' => City::first()->id
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('name');
    }



    public function testStringValidationForName()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $response = $this->actingAs($user)->postJson('/api/hotels', [
            'name' => 123,
            'city_id' => City::first()->id
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('name');
    }

    public function testMaxValidationIs50ForName()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $response = $this->actingAs($user)->postJson('/api/hotels', [
            'name' => Str::random(51),
            'city_id' => City::first()->id,
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('name');
    }


    public function testMinValidationIs3ForName()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $response = $this->actingAs($user)->postJson('/api/hotels', [
            'name' => Str::random(2),
            'city_id' => City::first()->id,
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('name');
    }

    public function testUniqueValidationForName()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $response = $this->actingAs($user)->postJson('/api/hotels', [
            'name' => Hotel::first()->name,
            'city_id' => City::first()->id,
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('name');
    }

    public function testRequiredValidationForCityId()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $response = $this->actingAs($user)->postJson('/api/hotels', [
            'name' => Str::random(10),
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('city_id');
    }

    public function testExistsValidationForCityId()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $response = $this->actingAs($user)->postJson('/api/hotels', [
            'name' => Str::random(10),
            'city_id' => City::query()->max('id') + 1
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('city_id');
    }


    public function testCanStoreHotelAsAuthenticated()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $hotelsCount = Hotel::count();
        $response = $this->actingAs($user)->postJson('api/hotels',[
            "name" => Str::random(10),
            'city_id' => City::first()->id
        ]);
        $this->assertDatabaseCount('hotels',$hotelsCount + 1);
        $response->assertStatus(201);
    }

    private function prepareData()
    {
        Country::factory()
               ->has(City::factory()
                         ->has(Hotel::factory()
                                    ->count(1))
                         ->count(1))
               ->count(1)
               ->create();
    }
}
