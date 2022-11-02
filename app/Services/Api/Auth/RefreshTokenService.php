<?php


namespace App\Services\Api\Auth;


use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class RefreshTokenService
{
    public function execute(Authenticatable $user)
    {
        $user->tokens()->delete();
        return [
            'user'          => $user,
            'auth_token'    => $user->createAuthToken('auth'),
            'refresh_token' => $user->createRefreshToken('refresh')
        ];
    }
}
