<?php


namespace Tests\Feature;


use App\Models\City;
use App\Models\Country;
use App\Models\Facility;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomFacility;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function testCantFetchReservationsWithoutAuthenticated()
    {
        $response = $this->getJson(route('reservations.index'));
        $response->assertStatus(401);
    }

    public function testCanFetchReservationsAsAuthenticated()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson(route('reservations.index'));
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->hasAll('data', 'links', 'meta', 'message'));
    }


    public function testCanLimitReservationsWithPerPageAsQueryString()
    {
        $this->prepareData();
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->getJson(route('reservations.index',['per_page' => 1]));
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) =>
        $json->hasAll('data', 'links', 'meta', 'message')
             ->where('meta.per_page',1)
        );
    }

    public function testCantLimitReservationsWithPerPageBy0AsQueryString()
    {
        $this->prepareData();
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->getJson(route('reservations.index',['per_page' => 0]));
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('per_page');
    }

    public function testCantLimitReservationsWithPerPageByStringValueAsQueryString()
    {
        $this->prepareData();
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->getJson(route('reservations.index',['per_page' => "stringValue"]));
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('per_page');
    }


    public function testCantLimitReservationsWithPerPageBy51AsQueryString()
    {
        $this->prepareData();
        $user     = User::factory()->create();
        $response = $this->actingAs($user)->getJson(route('reservations.index',['per_page' => 51]));
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('per_page');
    }

    public function testCanSearchByHotelNameAsSearchQueryString()
    {
        $this->prepareData();
        $user = User::factory()->create();
        $querySearchParam =  Hotel::first()->name;
        $count = Room::query()
                     ->where(function ($q) use ($querySearchParam) {
                         $q->whereRelation('hotel.city.country','iso_code','like',"%$querySearchParam%")
                           ->orwhereRelation('hotel','name','like',"%$querySearchParam%")
                           ->orwhereRelation('hotel.city','name','like',"%$querySearchParam%")
                           ->orWhere('price_per_night',"$querySearchParam");
                     })
                     ->count();
        $response = $this->actingAs($user)->getJson(route('reservations.index', [ 'search' => $querySearchParam ]));
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->hasAll('data', 'links', 'meta', 'message')->where('meta.total' ,$count));
    }

    public function testCanSearchByIsoCountryCodeAsSearchQueryString()
    {
        $this->prepareData();
        $user = User::factory()->create();
        $querySearchParam =  Country::first()->iso_code;
        $count = Room::query()
                     ->where(function ($q) use ($querySearchParam) {
                         $q->whereRelation('hotel.city.country','iso_code','like',"%$querySearchParam%")
                           ->orwhereRelation('hotel','name','like',"%$querySearchParam%")
                           ->orwhereRelation('hotel.city','name','like',"%$querySearchParam%")
                           ->orWhere('price_per_night',"$querySearchParam");
                     })
                     ->count();
        $response = $this->actingAs($user)->getJson(route('reservations.index',['search'=>$querySearchParam]));
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->hasAll('data', 'links', 'meta', 'message')->where('meta.total' ,$count));
    }

    public function testCanSearchByCityNameAsSearchQueryString()
    {
        $this->prepareData();
        $user = User::factory()->create();
        $querySearchParam =  City::first()->name;
        $count = Room::query()
                     ->where(function ($q) use ($querySearchParam) {
                         $q->whereRelation('hotel.city.country','iso_code','like',"%$querySearchParam%")
                           ->orwhereRelation('hotel','name','like',"%$querySearchParam%")
                           ->orwhereRelation('hotel.city','name','like',"%$querySearchParam%")
                           ->orWhere('price_per_night',"$querySearchParam");
                     })
                     ->count();
        $response = $this->actingAs($user)->getJson(route('reservations.index',['search'=>$querySearchParam]));
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->hasAll('data', 'links', 'meta', 'message')->where('meta.total' ,$count));
    }


    public function testCanSearchByPricePerNightAsSearchQueryString()
    {
        $this->prepareData();
        $user = User::factory()->create();
        $querySearchParam =  Room::first()->price_per_night;
        $count = Room::query()
                     ->where(function ($q) use ($querySearchParam) {
                         $q->whereRelation('hotel.city.country','iso_code','like',"%$querySearchParam%")
                           ->orwhereRelation('hotel','name','like',"%$querySearchParam%")
                           ->orwhereRelation('hotel.city','name','like',"%$querySearchParam%")
                           ->orWhere('price_per_night',"$querySearchParam");
                     })
                     ->count();
        $response = $this->actingAs($user)->getJson(route('reservations.index',['search'=>$querySearchParam]));
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->hasAll('data', 'links', 'meta', 'message')->where('meta.total' ,$count));
    }

    public function testCanSortByHotelNameQueryStringInDescOrder()
    {

        $this->prepareData();
        $user = User::factory()->create();
        $expectedData = Room::query()->join('hotels','hotels.id','rooms.hotel_id')->orderBy('hotels.name','desc')->select('hotels.name as hotel_name')->limit(10)->get();
        $response = $this->actingAs($user)->getJson(route('reservations.index',['sort_by'=>'hotel_name','sort_direction' => 'desc']));
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->hasAll('data', 'links', 'meta', 'message'));
        foreach ($expectedData as $idx => $expectedHotel)
            $this->assertEquals($expectedHotel->hotel_name,$response->json('data')[$idx]['hotel_name']);
    }

    public function testCanSortByHotelNameQueryStringInAscOrder()
    {

        $this->prepareData();
        $user = User::factory()->create();
        $expectedData = Room::query()
                            ->join('hotels','hotels.id','rooms.hotel_id')
                            ->orderBy('hotels.name','asc')
                            ->select('hotels.name as hotel_name')
                            ->limit(10)
                            ->get();
        $response = $this->actingAs($user)->getJson(route('reservations.index',['sort_by'=>'hotel_name','sort_direction' => 'asc']));
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->hasAll('data', 'links', 'meta', 'message'));
        foreach ($expectedData as $idx => $expectedHotel)
            $this->assertEquals($expectedHotel->hotel_name,$response->json('data')[$idx]['hotel_name']);
    }


    public function testCanSortByCountryQueryStringInDescOrder()
    {

        $this->prepareData();
        $user = User::factory()->create();
        $expectedData = Room::query()
                            ->join('hotels','hotels.id','rooms.hotel_id')
                            ->join('cities','cities.id','hotels.city_id')
                            ->join('countries','countries.id','cities.country_id')
                            ->orderBy('countries.name','desc')
                            ->select('countries.iso_code as country_iso_code')
                            ->limit(10)
                            ->get();
        $response = $this->actingAs($user)->getJson(route('reservations.index',['sort_by'=>'country','sort_direction' => 'desc']));
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->hasAll('data', 'links', 'meta', 'message'));
        foreach ($expectedData as $idx => $expectedHotel)
            $this->assertEquals($expectedHotel->country_iso_code,$response->json('data')[$idx]['country_iso_code']);
    }


    public function testCanSortByCountryQueryStringInAscOrder()
    {

        $this->prepareData();
        $user = User::factory()->create();
        $expectedData = Room::query()
                            ->join('hotels','hotels.id','rooms.hotel_id')
                            ->join('cities','cities.id','hotels.city_id')
                            ->join('countries','countries.id','cities.country_id')
                            ->orderBy('countries.name','asc')
                            ->select('countries.iso_code as country_iso_code')
                            ->limit(10)
                            ->get();
        $response = $this->actingAs($user)->getJson(route('reservations.index',['sort_by'=>'country','sort_direction' => 'asc']));
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->hasAll('data', 'links', 'meta', 'message'));
        foreach ($expectedData as $idx => $expectedHotel)
            $this->assertEquals($expectedHotel->country_iso_code,$response->json('data')[$idx]['country_iso_code']);
    }

    public function testCanSortByCityQueryStringInAscOrder()
    {

        $this->prepareData();
        $user = User::factory()->create();
        $expectedData = Room::query()
                            ->join('hotels','hotels.id','rooms.hotel_id')
                            ->join('cities','cities.id','hotels.city_id')
                            ->orderBy('cities.name','asc')
                            ->select('cities.name as city_name')
                            ->limit(10)
                            ->get();
        $response = $this->actingAs($user)->getJson(route('reservations.index',['sort_by'=>'city','sort_direction' => 'asc']));
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->hasAll('data', 'links', 'meta', 'message'));
        foreach ($expectedData as $idx => $expectedHotel)
            $this->assertEquals($expectedHotel->city_name,$response->json('data')[$idx]['city_name']);
    }


    public function testCanSortByCityQueryStringInDescOrder()
    {

        $this->prepareData();
        $user = User::factory()->create();
        $expectedData = Room::query()
                            ->join('hotels','hotels.id','rooms.hotel_id')
                            ->join('cities','cities.id','hotels.city_id')
                            ->orderBy('cities.name','desc')
                            ->select('cities.name as city_name')
                            ->limit(10)
                            ->get();
        $response = $this->actingAs($user)->getJson(route('reservations.index',['sort_by'=>'city','sort_direction' => 'desc']));
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->hasAll('data', 'links', 'meta', 'message'));
        foreach ($expectedData as $idx => $expectedHotel)
            $this->assertEquals($expectedHotel->city_name,$response->json('data')[$idx]['city_name']);
    }


    public function testCanSortByPriceQueryStringInAscOrder()
    {

        $this->prepareData();
        $user = User::factory()->create();
        $expectedData = Room::query()
                            ->join('hotels','hotels.id','rooms.hotel_id')
                            ->orderBy('rooms.price_per_night','asc')
                            ->select('rooms.price_per_night as price')
                            ->limit(10)
                            ->get();
        $response = $this->actingAs($user)->getJson(route('reservations.index',['sort_by'=>'price','sort_direction' => 'asc']));
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->hasAll('data', 'links', 'meta', 'message'));
        foreach ($expectedData as $idx => $expectedHotel)
            $this->assertEquals($expectedHotel->price,$response->json('data')[$idx]['price_per_night']);
    }

    public function testCantSortByDirectionUnlessAscOrDescOnly()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson(route('reservations.index',['sort_direction' => 'not_asc_or_desc']));
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('sort_direction');
    }

    public function testCanSortByOnlyAllowedSorts()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson(route('reservations.index',['sort_by' => 'not_allowed_sort_column']));
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('sort_by');
    }



    public function testCanSortByPriceQueryStringInDescOrder()
    {

        $this->prepareData();
        $user = User::factory()->create();
        $expectedData = Room::query()
                            ->join('hotels','hotels.id','rooms.hotel_id')
                            ->orderBy('rooms.price_per_night','desc')
                            ->select('rooms.price_per_night as price')
                            ->limit(10)
                            ->get();
        $response = $this->actingAs($user)->getJson(route('reservations.index',['sort_by'=>'price','sort_direction' => 'desc']));
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->hasAll('data', 'links', 'meta', 'message'));
        foreach ($expectedData as $idx => $expectedHotel)
            $this->assertEquals($expectedHotel->price,$response->json('data')[$idx]['price_per_night']);
    }

    private function prepareData()
    {

        Country::factory()
               ->has(City::factory()
                         ->has(Hotel::factory()
                                    ->has(Room::factory()->count(10))
                                    ->count(5))
                         ->count(5))
               ->count(5)
               ->create();

        Facility::factory()->count(1)->create()->each(function ($facility){
            $facility->rooms()->attach(Room::query()->inRandomOrder()->take(1)->pluck('id'));
        });
    }

    private function deleteExistsDbData()
    {
        DB::unprepared('SET FOREIGN_KEY_CHECKS = 0');
        User::truncate();
        RoomFacility::truncate();
        Room::truncate();
        Facility::truncate();
        Hotel::truncate();
        City::truncate();
        Country::truncate();
        DB::unprepared('SET FOREIGN_KEY_CHECKS = 1');
    }
}
