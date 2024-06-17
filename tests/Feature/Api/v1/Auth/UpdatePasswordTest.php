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
        // $this->withoutExceptionHandling();
        $data = [
            'old_password' => 'password',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/password", $data);

        // $response->dd();
        $response->assertStatus(200);

        $user = User::find(1);
        $this->assertTrue(Hash::check('newpassword', $user->password));
    }
}
