<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FacilityIndexRequest;
use App\Http\Resources\FacilityResource;
use App\Services\Facility\FetchFacilityListService;

class FacilityController extends Controller
{
    public function __invoke(FacilityIndexRequest $request,FetchFacilityListService $fetchFacilityListService)
    {
        return apiResponse()->success()
                            ->data(FacilityResource::collection(
                                $fetchFacilityListService->execute(
                                    (int) $request->per_page
                                )
                            ))
                            ->send();
    }
}
