<?php


use App\Helpers\ApiResponse\Factory\ApiResponseFactory;

function apiResponse()
{
    return app(ApiResponseFactory::class);
}
