<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\City\CityIndexRequest;
use App\Http\Resources\CityResource;
use App\Services\Api\City\FetchCityListService;

class CityController extends Controller
{
    public function __invoke(CityIndexRequest $request, FetchCityListService $fetchCityListService)
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
}
