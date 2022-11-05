<?php


namespace Tests\Feature;


use App\Models\City;
use App\Models\Country;
use App\Models\Facility;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class FacilityTest extends TestCase
{
    use RefreshDatabase;

    public function testCantFetchFacilitiesWithoutAuthenticated()
    {
        $response = $this->getJson('api/facilities');
        $response->assertStatus(401);
    }

    public function testCanFetchFacilitiesWithAuthenticated()
    {
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->getJson('api/facilities');
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->hasAll('data', 'links', 'meta', 'message')
        );
    }


    public function testCanLimitFacilitiesWithPerPageAsQueryString()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $response = $this->actingAs($user)->getJson("api/facilities?per_page=1");
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) =>
        $json->hasAll('data', 'links', 'meta', 'message')
             ->where('meta.per_page',1)
        );
    }

    public function testCantLimitFacilitiesWithPerPageBy0AsQueryString()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $response = $this->actingAs($user)->getJson("api/facilities?per_page=0");
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('per_page');
    }

    public function testCantLimitFacilitiesWithPerPageByStringValueAsQueryString()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $response = $this->actingAs($user)->getJson("api/facilities?per_page=stringValue");
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('per_page');
    }


    public function testCantLimitFacilitiesWithPerPageBy51AsQueryString()
    {
        $user     = User::factory()->create();
        $this->prepareData();
        $response = $this->actingAs($user)->getJson("api/facilities?per_page=51");
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('per_page');
    }

    private function prepareData()
    {
        Facility::factory()->count(20)->create();
    }


}
