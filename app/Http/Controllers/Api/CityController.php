<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\City\CityIndexRequest;
use App\Http\Requests\City\CityStoreRequest;
use App\Http\Requests\City\CityUpdateRequest;
use App\Http\Resources\CityResource;
use App\Models\City;
use App\Services\City\DeleteCityService;
use App\Services\City\FetchCityListService;
use App\Services\City\FetchSingleCityService;
use App\Services\City\StoreCityService;
use App\Services\City\UpdateCityService;

class CityController extends Controller
{
    public function index(CityIndexRequest $request, FetchCityListService $fetchCityListService)
    {
        return apiResponse()->success()
                            ->data(CityResource::collection(
                                $fetchCityListService->execute(
                                    $request->only('country'),
                                    (int) $request->per_page
                                )
                            ))
                            ->send();
    }

    public function show(City $city,FetchSingleCityService $fetchSingleCityService)
    {
        return apiResponse()->success()
                            ->data(CityResource::make($fetchSingleCityService->execute($city)))
                            ->send();
    }

    public function store(CityStoreRequest $request, StoreCityService $storeCityService)
    {
        return apiResponse()->success()
                            ->data(CityResource::make(
                                $storeCityService->execute($request->validated()))
                            )
                            ->message(__('city.success.stored'))
                            ->send();
    }

    public function update(City $city,CityUpdateRequest $request,UpdateCityService $updateCityService)
    {
        return apiResponse()->success()
                            ->data(CityResource::make(
                                $updateCityService->execute($city,$request->validated()))
                            )
                            ->message(__('city.success.updated'))
                            ->send();
    }

    public function destroy(City $city,DeleteCityService $deleteCityService)
    {
        return apiResponse()->success()
                            ->data(CityResource::make(
                                $deleteCityService->execute($city))
                            )
                            ->message(__('city.success.deleted'))
                            ->send();
    }

}
