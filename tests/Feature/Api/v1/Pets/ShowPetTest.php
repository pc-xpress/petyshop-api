<?php

namespace Tests\Feature\Api\v1\Pets;

use App\Models\Pet;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowPetTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_unauthenticated_user_must_see_of_one_their_pets(): void
    {
        // $this->withoutExceptionHandling();
        $response = $this->apiAs(User::find(1), 'GET', "{$this->apiV1Base}/pets/{$this->pet->id}");
        // $response->dd();
        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'success', 'errors', 'message', 'data' => [
            'pet' =>
            [
                'id',
                'name',
                'slug',
                'species',
                'breed',
                'age',
                'biography',
                'profile_picture',
            ]
        ]]);

        $response->assertJsonFragment([
            'id' => $this->pet->id,
            'name' => $this->pet->name,
            'species' => $this->pet->species,
            'breed' => $this->pet->breed,
            'age' => $this->pet->age,
            'biography' => $this->pet->biography,
            'profile_picture' => $this->pet->profile_picture,
        ]);
    }

    #[Test]
    public function a_unauthenticated_user_cannot_see_any_pet(): void
    {
        $response = $this->getJson("{$this->apiV1Base}/pets/{$this->pet->id}");

        $response->assertStatus(401);
    }

    #[Test]
    public function an_authenticated_user_must_see_only_their_pets(): void
    {
        $user = User::factory()->create();
        $response = $this->apiAs($user, 'GET', "{$this->apiV1Base}/pets/{$this->pet->id}");
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
