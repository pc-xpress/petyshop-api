<?php

namespace Tests\Feature\Api\v1\Auth;

use Tests\TestCase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRegisterTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_user_can_register(): void
    {
        $data = [
            'email' => 'email@email.com',
            'password' => 'password',
            'name' => 'example',
            'last_name' => 'example example',
        ];

        $response = $this->postJson("{$this->apiV1Base}/users", $data);

        $response->assertStatus(200);
        $this->assertDatabaseCount('users', 1);
        $response->assertJsonStructure(['status', 'success', 'errors', 'message', 'data' => [
            'user' =>
            [
                'id',
                'email',
                'name',
                'last_name'
            ]
        ]]);

        $response->assertJsonFragment([
            'success' => true,
            'status' => 200,
            'errors' => [],
            'message' => 'OK',
            'data' => [
                'user' => [
                    'id' => 1,
                    'email' => 'email@email.com',
                    'name' => 'example',
                    'last_name' => 'example example',
                ],

            ]
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'email@email.com',
            'name' => 'example',
            'last_name' => 'example example',
        ]);
    }

    #[Test]
    public function a_registered_user_can_login(): void
    {
        $data = [
            'email' => 'email@email.com',
            'password' => 'password',
            'name' => 'example',
            'last_name' => 'example example',
        ];

        $response = $this->postJson("{$this->apiV1Base}/users", $data);
        $response = $this->postJson('/api/v1/login', ['email' => 'email@email.com', 'password' => 'password']);

        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'data' => ['token']]);
        $response->assertJsonFragment(['status' => 200, 'message' => 'OK']);
    }

    #[Test]
    public function email_most_be_required(): void
    {
        $data = [
            'email' => '',
            'password' => 'password',
            'name' => 'example',
            'last_name' => 'example example',
        ];

        $response = $this->postJson("{$this->apiV1Base}/users", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'data',
                'status',
                'message',
                'errors' => ['email']
            ]
        );
    }

    #[Test]
    public function email_most_be_valid_email(): void
    {
        $data = [
            'email' => 'dsadsda',
            'password' => 'password',
            'name' => 'example',
            'last_name' => 'example example',
        ];

        $response = $this->postJson("{$this->apiV1Base}/users", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'data',
                'status',
                'message',
                'errors' => ['email']
            ]
        );
    }

    #[Test]
    public function email_most_be_a_string(): void
    {
        $data = [
            'email' => 123654,
            'password' => 'password',
            'name' => 'example',
            'last_name' => 'example example',
        ];

        $response = $this->postJson("{$this->apiV1Base}/users", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'data',
                'status',
                'message',
                'errors' => ['email']
            ]
        );
    }

    #[Test]
    public function email_most_be_unique(): void
    {
        User::factory()->create(['email' => 'email@email.com', 'last_name' => 'example example']);
        $data = [
            'email' => 'email@email.com',
            'password' => 'password',
            'name' => 'example',
            'last_name' => 'example example',
        ];

        $response = $this->postJson("{$this->apiV1Base}/users", $data);

        // $response->dd();
        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'data',
                'status',
                'message',
                'errors' => ['email']
            ]
        );
    }

    #[Test]
    public function password_most_be_required(): void
    {
        $data = [
            'email' => 'email@email.com',
            'password' => '',
            'name' => 'example',
            'last_name' => 'example example',
        ];

        $response = $this->postJson("{$this->apiV1Base}/users", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors' => ['password']]);
    }

    #[Test]
    public function password_most_be_a_lease_8_characters(): void
    {
        $data = [
            'email' => 'email@email.com',
            'password' => '1234',
            'name' => 'example',
            'last_name' => 'example example',
        ];

        $response = $this->postJson("{$this->apiV1Base}/users", $data);


        $response->assertStatus(422);
        $response->assertJsonStructure(['errors' => ['password']]);
    }

    #[Test]
    public function name_most_be_required(): void
    {
        $data = [
            'email' => 'email@email.com',
            'password' => 'password',
            'name' => '',
            'last_name' => 'example example',
        ];

        $response = $this->postJson("{$this->apiV1Base}/users", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors' => ['name']]);
    }

    #[Test]
    public function name_most_be_a_lease_2_characters(): void
    {
        $data = [
            'email' => 'email@email.com',
            'password' => 'password',
            'name' => '1',
            'last_name' => 'example example',
        ];

        $response = $this->postJson("{$this->apiV1Base}/users", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors' => ['name']]);
    }

    #[Test]
    public function last_name_most_be_required(): void
    {
        $data = [
            'email' => 'email@email.com',
            'password' => 'password',
            'name' => 'example',
            'last_name' => '',
        ];

        $response = $this->postJson("{$this->apiV1Base}/users", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors' => ['last_name']]);
    }

    #[Test]
    public function last_name_most_be_a_lease_2_characters(): void
    {
        $data = [
            'email' => 'email@email.com',
            'password' => 'password',
            'name' => 'example',
            'last_name' => '1',
        ];

        $response = $this->postJson("{$this->apiV1Base}/users", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors' => ['last_name']]);
    }
}
