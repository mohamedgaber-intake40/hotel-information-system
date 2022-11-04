<?php


namespace App\Http\Controllers\Api\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\TokenResource;
use App\Http\Resources\UserResource;
use App\Services\Auth\LoginService;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request, LoginService $loginService)
    {
        [
            'user'          => $user,
            'auth_token'    => $authToken,
            'refresh_token' => $refreshToken
        ] = $loginService->execute($request->email, $request->password);

        return apiResponse()->success()
                            ->data([
                                       'user'          => UserResource::make($user),
                                       'auth_token'    => TokenResource::make($authToken),
                                       'refresh_token' => TokenResource::make($refreshToken),
                                   ])
                            ->message(__('auth.success.login'))
                            ->send();

    }
}
