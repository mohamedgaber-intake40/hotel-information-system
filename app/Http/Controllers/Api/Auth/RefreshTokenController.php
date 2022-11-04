<?php


namespace App\Http\Controllers\Api\Auth;


use App\Http\Controllers\Controller;
use App\Http\Resources\TokenResource;
use App\Http\Resources\UserResource;
use App\Services\Auth\RefreshTokenService;

class RefreshTokenController extends Controller
{
    public function __invoke(RefreshTokenService $refreshTokenService)
    {
        [
            'user'          => $user,
            'auth_token'    => $authToken,
            'refresh_token' => $refreshToken
        ] = $refreshTokenService->execute(auth()->user());
        return apiResponse()->success()
                            ->data([
                                       'user'          => UserResource::make($user),
                                       'auth_token'    => TokenResource::make($authToken),
                                       'refresh_token' => TokenResource::make($refreshToken),
                                   ])
                            ->message(__('auth.success.refresh'))
                            ->send();
    }
}
