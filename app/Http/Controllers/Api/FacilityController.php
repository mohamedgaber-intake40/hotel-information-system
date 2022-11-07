<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Facility\FacilityIndexRequest;
use App\Http\Requests\Facility\FacilityStoreRequest;
use App\Http\Requests\Facility\FacilityUpdateRequest;
use App\Http\Resources\FacilityResource;
use App\Models\Facility;
use App\Services\Facility\DeleteFacilityService;
use App\Services\Facility\FetchFacilityListService;
use App\Services\Facility\FetchSingleFacilityService;
use App\Services\Facility\StoreFacilityService;
use App\Services\Facility\UpdateFacilityService;

class FacilityController extends Controller
{
    public function index(FacilityIndexRequest $request,FetchFacilityListService $fetchFacilityListService)
    {
        return apiResponse()->success()
                            ->data(FacilityResource::collection(
                                $fetchFacilityListService->execute(
                                    (int) $request->per_page
                                )
                            ))
                            ->send();
    }

    public function show(Facility $facility,FetchSingleFacilityService $fetchSingleFacilityService)
    {
        return apiResponse()->success()
                            ->data(FacilityResource::make($fetchSingleFacilityService->execute($facility)))
                            ->send();
    }

    public function store(FacilityStoreRequest $request, StoreFacilityService $storeFacilityService)
    {
        return apiResponse()->success()
                            ->data(FacilityResource::make(
                                $storeFacilityService->execute($request->validated()))
                            )
                            ->message(__('facility.success.stored'))
                            ->send();
    }

    public function update(Facility $facility,FacilityUpdateRequest $request,UpdateFacilityService $updateFacilityService)
    {
        return apiResponse()->success()
                            ->data(FacilityResource::make(
                                $updateFacilityService->execute($facility,$request->validated()))
                            )
                            ->message(__('facility.success.updated'))
                            ->send();
    }

    public function destroy(Facility $facility,DeleteFacilityService $deleteFacilityService)
    {
        $deleteFacilityService->execute($facility);
        return apiResponse()->success()
                            ->message(__('facility.success.deleted'))
                            ->send();
    }
}
