<?php

namespace Tests\Feature\Api\v1\Auth;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    #[Test]
    public function an_existing_user_can_login(): void
    {

        // $this->withoutExceptionHandling();

        $credentials = [
            'email' => 'example@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/v1/login', $credentials);
        // $response->dd();
        // dump($response);
        // dd($response);

        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'data' => ['token']]);
        $response->assertJsonFragment(['status' => 200, 'message' => 'OK']);
    }

    #[Test]
    public function an_non_existing_user_cannot_login(): void
    {
        $credentials = [
            'email' => 'example@notexisting.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/v1/login', $credentials);

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
        $response->assertJsonFragment(['status' => 401, 'message' => 'Unauthorized']);
    }

    #[Test]
    public function email_most_be_required(): void
    {
        $credentials = [
            'password' => 'password',
        ];

        $response = $this->postJson('/api/v1/login', $credentials);

        $response->assertStatus(422);
        $response->assertJsonStructure(['data', 'status', 'message', 'errors' => ['email']]);
        $response->assertJsonFragment(['status' => 422, 'message' => 'The email field is required.']);
    }

    #[Test]
    public function password_most_be_required(): void
    {
        $credentials = [
            'email' => 'example@notexisting.com',
        ];

        $response = $this->postJson('/api/v1/login', $credentials);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors' => ['password']]);
        $response->assertJsonFragment(['status' => 422, 'message' => 'The password field is required.']);
    }
}
