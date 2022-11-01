<?php


namespace App\Helpers\ApiResponse\Factory;



use App\Helpers\ApiResponse\Builder\ErrorApiResponseBuilder;
use App\Helpers\ApiResponse\Builder\SuccessApiResponseBuilder;

class ApiResponseFactory
{
    public static function make()
    {
        return app(static::class);
    }

    public function success()
    {
        return app(SuccessApiResponseBuilder::class);
    }

    public function error()
    {
        return app(ErrorApiResponseBuilder::class);
    }
}
