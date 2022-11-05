<?php


namespace Tests\Feature;


use App\Models\City;
use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CountryTest extends TestCase
{
    use RefreshDatabase;

    public function testCantFetchCountriesWithoutAuthenticated()
    {
        $response = $this->getJson('api/countries');
        $response->assertStatus(401);
    }

    public function testCanFetchCountriesWithAuthenticated()
    {
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->getJson('api/countries');
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->hasAll('data', 'links', 'meta', 'message')
        );
    }


    public function testCanLimitCountriesWithPerPageAsQueryString()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $response = $this->actingAs($user)->getJson("api/countries?per_page=1");
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) =>
        $json->hasAll('data', 'links', 'meta', 'message')
             ->where('meta.per_page',1)
        );
    }

    public function testCantLimitCountriesWithPerPageBy0AsQueryString()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $response = $this->actingAs($user)->getJson("api/countries?per_page=0");
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('per_page');
    }

    public function testCantLimitCountriesWithPerPageByStringValueAsQueryString()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $response = $this->actingAs($user)->getJson("api/countries?per_page=stringValue");
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('per_page');
    }


    public function testCantLimitCountriesWithPerPageBy51AsQueryString()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $response = $this->actingAs($user)->getJson("api/countries?per_page=51");
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
