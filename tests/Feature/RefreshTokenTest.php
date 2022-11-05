<?php


namespace Tests\Feature;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RefreshTokenTest extends TestCase
{
    use RefreshDatabase;

    public function testAuthenticatedProtectedRefreshTokenApi()
    {
        $response = $this->getJson('/api/auth/refresh-token');
        $response->assertStatus(401);
    }


    public function testCantRefreshTokenWithAuthToken()
    {
        $user      = User::factory()->create();
        $authToken = $user->createAuthToken('auth');
        $response  = $this->getJson('/api/auth/refresh-token', [ 'authorization' => 'Bearer ' . $authToken->plainTextToken ]);
        $response->assertStatus(401);
    }

    public function testCanRefreshTokenWithRefreshToken()
    {
        $user         = User::factory()->create();
        $refreshToken = $user->createRefreshToken('refresh');
        $response     = $this->getJson('/api/auth/refresh-token', [ 'authorization' => 'Bearer ' . $refreshToken->plainTextToken ]);
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->hasAll('data', 'data.user', 'data.auth_token', 'data.refresh_token', 'message')
        );
    }

}
