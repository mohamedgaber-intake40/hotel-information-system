<?php


namespace App\Helpers\ApiResponse\Builder;


abstract class BaseApiResponseBuilder
{
//    public function asa(){
//        responseBuilder()->success()->data()->message()->statusCode();
//    }

    protected $message;
    protected $statusCode;


    public function message($message)
    {
        $this->message = $message;
        return $this;
    }

    public function statusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    protected function responseData()
    {
        return [
            'message'    => $this->message,
        ];
    }

    public function send()
    {
        return response()->json($this->responseData(),$this->statusCode)->send();
    }


}
