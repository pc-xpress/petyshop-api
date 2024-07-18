<?php

namespace Tests\Feature\Api\v1\Pets;

use App\Models\Pet;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PetDeleteTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_unauthenticated_user_must_delete_their_pets(): void
    {
        // $this->withoutExceptionHandling();
        $response = $this->apiAs(User::find(1), 'DELETE', "{$this->apiV1Base}/pets/{$this->pet->id}");
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'OK',
        ]);
        $this->assertDatabaseCount('pets', 0);
    }

    #[Test]
    public function a_unauthenticated_user_cannot_edit_a_pet(): void
    {
        $response = $this->deleteJson("{$this->apiV1Base}/pets/{$this->pet->id}");

        $response->assertStatus(401);
    }

    #[Test]
    public function an_authenticated_user_must_edit_only_their_pets(): void
    {
        $user = User::factory()->create();
        $response = $this->apiAs($user, 'DELETE', "{$this->apiV1Base}/pets/{$this->pet->id}");
        $response->assertStatus(403);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
        $this->pet = Pet::factory()->create([
            'user_id' => 1,
        ]);
    }
}
