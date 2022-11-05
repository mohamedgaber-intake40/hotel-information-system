<?php


namespace Tests\Feature;


use App\Models\City;
use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CityTest extends TestCase
{
    use RefreshDatabase;

    public function testCantFetchCitiesWithoutAuthenticated()
    {
        $response = $this->getJson('api/cities');
        $response->assertStatus(401);
    }

    public function testCanFetchCitiesWithAuthenticated()
    {
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->getJson('api/cities');
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->hasAll('data', 'links', 'meta', 'message')
        );
    }

    public function testCanFilterCitiesWithCountryAsQueryString()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $country = Country::first();
        $response = $this->actingAs($user)->getJson("api/cities?country=$country->id");
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) =>
            $json->hasAll('data', 'links', 'meta', 'message')
                 ->where('meta.total',3)
        );
    }

    public function testCanLimitCitiesWithPerPageAsQueryString()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $response = $this->actingAs($user)->getJson("api/cities?per_page=1");
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) =>
        $json->hasAll('data', 'links', 'meta', 'message')
             ->where('meta.per_page',1)
        );
    }

    public function testCantLimitCitiesWithPerPageBy0AsQueryString()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $response = $this->actingAs($user)->getJson("api/cities?per_page=0");
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('per_page');
    }

    public function testCantLimitCitiesWithPerPageByStringValueAsQueryString()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $response = $this->actingAs($user)->getJson("api/cities?per_page=stringValue");
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('per_page');
    }


    public function testCantLimitCitiesWithPerPageBy51AsQueryString()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $response = $this->actingAs($user)->getJson("api/cities?per_page=51");
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('per_page');
    }

    private function prepareData()
    {
        Country::factory()
               ->has(City::factory()->count(3))
               ->count(3)
               ->create();
    }
}
