<?php

namespace Tests\Feature\Api\v1\Auth;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUserDataTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    #[Test]
    public function an_authenticated_user_can_modify_their_data(): void
    {
        $data = [
            'name' => 'newname',
            'last_name' => 'new lastname',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/profile", $data);

        $response->assertStatus(200);
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
                    'email' => 'example@example.com',
                    'name' => 'newname',
                    'last_name' => 'new lastname',
                ],

            ]
        ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'User',
            'last_name' => 'Test',
            'email' => 'example@example.com',
        ]);
    }

    #[Test]
    public function an_authenticated_user_cannot_modify_their_email(): void
    {
        $data = [
            'email' => 'newemail@newemail.com',
            'name' => 'newname',
            'last_name' => 'new lastname',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/profile", $data);

        $response->assertStatus(200);
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
                    'email' => 'example@example.com',
                    'name' => 'newname',
                    'last_name' => 'new lastname',
                ],

            ]
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'User',
            'name' => 'newname',
            'last_name' => 'new lastname',
        ]);
    }

    #[Test]
    public function an_authenticated_user_cannot_modify_their_password(): void
    {
        $data = [
            'password' => 'newpassword',
            'name' => 'newname',
            'last_name' => 'new lastname',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/profile", $data);

        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'success', 'errors', 'message', 'data' => [
            'user' =>
            [
                'id',
                'email',
                'name',
                'last_name'
            ]
        ]]);

        $user = User::find(1);

        $this->assertFalse(Hash::check('newpassword', $user->password));
    }

    #[Test]
    public function name_most_be_required(): void
    {
        $data = [
            'name' => '',
            'last_name' => 'example example',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/profile", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors' => ['name']]);
    }

    #[Test]
    public function name_most_be_a_lease_2_characters(): void
    {
        $data = [
            'name' => '1',
            'last_name' => 'example example',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/profile", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors' => ['name']]);
    }

    #[Test]
    public function last_name_most_be_required(): void
    {
        $data = [
            'name' => 'example',
            'last_name' => '',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/profile", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors' => ['last_name']]);
    }

    #[Test]
    public function last_name_most_be_a_lease_2_characters(): void
    {
        $data = [
            'name' => 'example',
            'last_name' => '1',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/profile", $data);


        $response->assertStatus(422);
        $response->assertJsonStructure(['errors' => ['last_name']]);
    }
}
