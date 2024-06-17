<?php

namespace Tests\Feature\Api\v1\Auth;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdatePasswordTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    #[Test]
    public function an_authenticated_user_can_update_their_password(): void
    {
        $data = [
            'current_password' => 'password',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/password", $data);

        $response->assertStatus(200);

        $user = User::find(1);
        $this->assertTrue(Hash::check('newpassword', $user->password));
    }

    #[Test]
    public function old_password_most_be_validated(): void
    {
        $data = [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/password", $data);

        $response->assertStatus(422);

        $response->assertJsonStructure(['status', 'success', 'message', 'errors' => ['current_password']]);
        $response->assertJsonFragment(['errors' => ['current_password' => ['The password does not match.']]]);
    }

    #[Test]
    public function old_password_most_be_required(): void
    {
        $data = [
            'current_password' => '',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/password", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['status', 'success', 'message', 'errors' => ['current_password']]);
    }

    #[Test]
    public function password_most_be_required(): void
    {
        $data = [
            'current_password' => 'password',
            'password' => '',
            'password_confirmation' => 'newpassword',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/password", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['status', 'success', 'message', 'errors' => ['password']]);
    }

    #[Test]
    public function password_most_be_confirmed(): void
    {
        $data = [
            'current_password' => 'password',
            'password' => 'newpassword',
            'password_confirmation' => '',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/password", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['status', 'success', 'message', 'errors' => ['password']]);
        $response->assertJsonFragment(['errors' => ['password' => ['The password field confirmation does not match.']]]);
    }
}
