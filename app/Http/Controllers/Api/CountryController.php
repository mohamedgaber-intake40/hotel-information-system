<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\Country\CountryIndexRequest;
use App\Http\Requests\Country\CountryStoreRequest;
use App\Http\Requests\Country\CountryUpdateRequest;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use App\Services\Country\FetchCountryListService;
use App\Services\Country\DeleteCountryService;
use App\Services\Country\FetchSingleCountryService;
use App\Services\Country\StoreCountryService;
use App\Services\Country\UpdateCountryService;

class CountryController extends Controller
{
    public function index(CountryIndexRequest $request, FetchCountryListService $fetchCountryListService)
    {
        return apiResponse()->success()
                            ->data(CountryResource::collection(
                                $fetchCountryListService->execute(
                                    (int)$request->per_page
                                )
                            ))
                            ->send();
    }

    public function show(Country $country, FetchSingleCountryService $fetchSingleCountryService)
    {
        return apiResponse()->success()
                            ->data(CountryResource::make($fetchSingleCountryService->execute($country)))
                            ->send();
    }

    public function store(CountryStoreRequest $request, StoreCountryService $storeCountryService)
    {
        return apiResponse()->success()
                            ->data(CountryResource::make(
                                $storeCountryService->execute($request->validated()))
                            )
                            ->message(__('country.success.stored'))
                            ->send();
    }

    public function update(Country $country, CountryUpdateRequest $request, UpdateCountryService $updateCountryService)
    {
        return apiResponse()->success()
                            ->data(CountryResource::make(
                                $updateCountryService->execute($country, $request->validated()))
                            )
                            ->message(__('country.success.updated'))
                            ->send();
    }

    public function destroy(Country $country, DeleteCountryService $deleteCountryService)
    {
        $this->authorize('delete',$country);
        $deleteCountryService->execute($country);
        return apiResponse()->success()
                            ->message(__('country.success.deleted'))
                            ->send();
    }
}
