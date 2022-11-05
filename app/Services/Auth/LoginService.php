<?php


namespace App\Services\Auth;


use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class LoginService
{
    public function execute($email, $password)
    {
        $user = User::where('email', $email)->first();
        if ( !$user || !Hash::check($password, $user->password) )
            return apiResponse()->error()
                                ->message(__('auth.failed'))
                                ->statusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                                ->send();
        return [
            'user'          => $user,
            'auth_token'    => $user->createAuthToken('auth'),
            'refresh_token' => $user->createRefreshToken('refresh')
        ];
    }
}
