<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\City\CityIndexRequest;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Services\City\FetchCityListService;
use App\Services\Country\FetchCountryListService;

class CountryController extends Controller
{
    public function __invoke(CityIndexRequest $request, FetchCountryListService $fetchCountryListService)
    {
        return apiResponse()->success()
                            ->data(CountryResource::collection(
                                $fetchCountryListService->execute(
                                    (int) $request->per_page
                                )
                            ))
                            ->send();
    }
}
