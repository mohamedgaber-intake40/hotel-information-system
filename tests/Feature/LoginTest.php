<?php


namespace Tests\Feature;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function testRequiredValidationForEmail()
    {
        $response = $this->postJson('/api/auth/login', [
            'password' => 'wrongpassword',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('email');
    }

    public function testEmailValidationForEmail()
    {
        $response = $this->postJson('/api/auth/login', [
            'email'    => 'notEmail',
            'password' => 'wrongpassword',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('email');
    }

    public function testMaxValidationIs100ForEmail()
    {
        $response = $this->postJson('/api/auth/login', [
            'email'    => Str::random(101),
            'password' => 'wrongpassword',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('email');
    }


    public function testRequiredValidationIsForPassword()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => "test@test.com",
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('password');
    }

    public function testStringValidationIsForPassword()
    {
        $response = $this->postJson('/api/auth/login', [
            'email'    => "test@test.com",
            'password' => 123456
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('password');
    }

    public function testMaxValidationIs100ForPassword()
    {
        $response = $this->postJson('/api/auth/login', [
            'email'    => "test@test.com",
            'password' => Str::random(101),
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('password');
    }

    public function testCantLoginWithWrongCredentials()
    {
        User::factory()->create();
        $response = $this->postJson('/api/auth/login', [
            'email'    => 'wrong@wrong.com',
            'password' => 'wrongpassword',
        ]);
        $response->assertStatus(422);
    }

    public function testCantLoginWithWrongEmail()
    {
        User::factory()->create();
        $response = $this->postJson('/api/auth/login', [
            'email'    => 'wrong@wrong.com',
            'password' => '123456',
        ]);
        $response->assertStatus(422);
    }

    public function testCantLoginWithWrongPassword()
    {
        $user     = User::factory()->create();
        $response = $this->postJson('/api/auth/login', [
            'email'    => $user->email,
            'password' => 'wrongpassword',
        ]);
        $response->assertStatus(422);
    }

    public function testCanLoginWithRightCredentials()
    {
        $user     = User::factory()->create();
        $response = $this->postJson('/api/auth/login', [
            'email'    => $user->email,
            'password' => "123456",
        ]);
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) =>
        $json->hasAll('data', 'data.user', 'data.auth_token', 'data.refresh_token', 'message')
        );
    }


}
