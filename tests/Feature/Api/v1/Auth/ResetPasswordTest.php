<?php

namespace Tests\Feature\Api\v1\Auth;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;
    protected string $token = '';
    protected string $email = '';

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    #[Test]
    public function an_existing_user_can_reset_their_password(): void
    {
        $this->sendResetPassword();

        $response = $this->putJson("{$this->apiV1Base}/reset-password?token={$this->token}", [
            'email' => $this->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword'
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['status' => 200, 'message' => 'OK']);
        $response->assertJsonStructure(['status', 'success', 'message', 'errors', 'data']);
        $user = User::find(1);
        $this->assertTrue(Hash::check('newpassword', $user->password));
    }

    #[Test]
    public function email_most_be_required(): void
    {
        $data = [
            'email' => '',
        ];

        $response = $this->postJson("{$this->apiV1Base}/reset-password", $data);

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
            'email' => 'notemail',
        ];

        $response = $this->postJson("{$this->apiV1Base}/reset-password", $data);

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
            'email' => 123546,
        ];

        $response = $this->postJson("{$this->apiV1Base}/reset-password", $data);

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
    public function email_must_be_an_existing_email(): void
    {
        $data = [
            'email' => 'notexisting@example.com',
        ];

        $response = $this->postJson("{$this->apiV1Base}/reset-password", $data);

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
    public function email_must_be_associated_with_the_token(): void
    {
        $this->sendResetPassword();

        $response = $this->putJson("{$this->apiV1Base}/reset-password?token={$this->token}dsdssd", [
            'email' => 'modifyemail@example.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword'
        ]);

        $response->assertStatus(500);
        $response->assertJsonStructure(
            [
                'data',
                'status',
                'message',
                'errors'
            ]
        );
        $response->assertJsonFragment(['message' => 'Invalid email.']);
    }

    #[Test]
    public function password_most_be_required(): void
    {
        $this->sendResetPassword();

        $response = $this->putJson("{$this->apiV1Base}/reset-password?token={$this->token}", [
            'email' => $this->email,
            'password' => '',
            'password_confirmation' => 'newpassword'
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'data',
                'status',
                'message',
                'errors' => ['password']
            ]
        );
        $response->assertJsonFragment(['errors' => ['password' => ['The password field is required.']]]);
    }

    #[Test]
    public function password_most_be_confirmed(): void
    {
        $this->sendResetPassword();

        $response = $this->putJson("{$this->apiV1Base}/reset-password?token={$this->token}", [
            'email' => $this->email,
            'password' => 'newpassword',
            'password_confirmation' => ''
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'data',
                'status',
                'message',
                'errors' => ['password']
            ]
        );
        $response->assertJsonFragment(['errors' => ['password' => ['The password field confirmation does not match.']]]);
    }

    #[Test]
    public function token_must_be_a_valid_token(): void
    {
        $this->sendResetPassword();

        $response = $this->putJson("{$this->apiV1Base}/reset-password?token={$this->token}dsdssd", [
            'email' => $this->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword'
        ]);

        $response->assertStatus(500);
        $response->assertJsonStructure(
            [
                'data',
                'status',
                'message',
                'errors'
            ]
        );
        $response->assertJsonFragment(['message' => 'Invalid token.']);
    }

    public function sendResetPassword()
    {
        Notification::fake();

        $data = [
            'email' => 'example@example.com',
        ];

        $response = $this->postJson("{$this->apiV1Base}/reset-password", $data);

        $response->assertStatus(200);
        $response->assertJsonFragment(['status' => 200, 'message' => 'OK']);

        $user = User::find(1);
        Notification::assertSentTo([$user], function (ResetPasswordNotification $notification) {
            $frontUrl = $notification->url;
            $parts = parse_url($frontUrl);
            parse_str($parts['query'], $query);
            $this->token = $query['token'];
            $this->email = $query['email'];

            return str_contains($frontUrl, env('APP_URL_FRONT') . '/reset-password?token=');
        });
    }
}
