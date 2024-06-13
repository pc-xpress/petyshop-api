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

        $response = $this->postJson("{$this->apiV1Base}/login", $credentials);
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

        $response = $this->postJson("{$this->apiV1Base}/login", $credentials);

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

        $response = $this->postJson("{$this->apiV1Base}/login", $credentials);

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
        $credentials = [
            'email' => 'password',
            'password' => 'password',
        ];

        $response = $this->postJson("{$this->apiV1Base}/login", $credentials);

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
        $credentials = [
            'email' => 25469,
            'password' => 'password',
        ];

        $response = $this->postJson("{$this->apiV1Base}/login", $credentials);

        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'data',
                'status',
                'message',
                'errors' => ['email']
            ]
        );

        // $response->assertJsonFragment([
        //     'errors' => [
        //         'email' => [
        //             'The email field must be a string.',
        //             'The email field must be a valid email address.'
        //         ]
        //     ]
        // ]);
    }

    #[Test]
    public function password_most_be_required(): void
    {
        $credentials = [
            'email' => 'example@notexisting.com',
        ];

        $response = $this->postJson("{$this->apiV1Base}/login", $credentials);


        $response->assertStatus(422);
        $response->assertJsonStructure(['errors' => ['password']]);
        // $response->assertJsonFragment(
        //     [
        //         'status' => 422,
        //         'message' => 'The password field is required.',
        //         'errors' => ['password' => ['The password field is required.']]
        //     ]
        // );
    }

    #[Test]
    public function password_most_be_a_lease_8_characters(): void
    {
        $credentials = [
            'email' => 'example@example.com',
            'password' => '123',
        ];

        $response = $this->postJson("{$this->apiV1Base}/login", $credentials);

        // $response->dd();

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors' => ['password']]);
        // $response->assertJsonFragment(
        //     [
        //         'status' => 422,
        //         'message' => 'The password field must be at least 8 characters.',
        //         'errors' => ['password' => ['The password field must be at least 8 characters.']]
        //     ]
        // );
    }
}
