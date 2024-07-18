<?php

namespace Tests\Feature\Api\v1\Pets;

use App\Models\Pet;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePetTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_user_can_create_a_pet(): void
    {
        // $this->withoutExceptionHandling();
        $data = [
            'name' => 'New Pet',
            'species' => 'New species',
            'breed' => 'New Breed',
            'age' => 2,
            'biography' => 'New Biography',
            'profile_picture' => 'photo-pet.png',
        ];

        $response = $this->apiAs(User::find(1), 'POST', "{$this->apiV1Base}/pets", $data);

        $response->assertStatus(200);
        $this->assertDatabaseCount('pets', 1);
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

        $pet = Pet::first();
        $this->assertStringContainsString('new-pet', $pet->slug);

        $this->assertDatabaseHas('pets', [
            'id' => 1,
            'user_id' => 1,
            'name' => 'New Pet',
            'species' => 'New species',
            'breed' => 'New Breed',
            'age' => 2,
            'biography' => 'New Biography',
            'profile_picture' => 'photo-pet.png',
        ]);
    }

    #[Test]
    public function a_unauthenticated_user_cannot_create_a_pet(): void
    {
        // $this->withoutExceptionHandling();
        $response = $this->postJson("{$this->apiV1Base}/pets");

        $response->assertStatus(401);
    }

    #[Test]
    public function name_most_be_required(): void
    {
        $data = [
            'name' => '',
            'species' => 'New species',
        ];

        $response = $this->apiAs(User::find(1), 'POST', "{$this->apiV1Base}/pets", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'data',
                'status',
                'message',
                'errors' => ['name']
            ]
        );
    }

    #[Test]
    public function species_most_be_required(): void
    {
        $data = [
            'name' => 'New name',
            'species' => '',
        ];

        $response = $this->apiAs(User::find(1), 'POST', "{$this->apiV1Base}/pets", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'data',
                'status',
                'message',
                'errors' => ['species']
            ]
        );
    }

    #[Test]
    public function name_most_be_a_string(): void
    {
        $data = [
            'name' => 1234,
            'species' => 'New species',
            'breed' => 'New Breed',
            'age' => 2,
            'biography' => 'New Biography',
            'profile_picture' => 'photo-pet.png',
        ];

        $response = $this->apiAs(User::find(1), 'POST', "{$this->apiV1Base}/pets", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'data',
                'status',
                'message',
                'errors' => ['name']
            ]
        );
    }

    #[Test]
    public function species_most_be_a_string(): void
    {
        $data = [
            'name' => 'New name',
            'species' => 1234,
            'breed' => 'New Breed',
            'age' => 2,
            'biography' => 'New Biography',
            'profile_picture' => 'photo-pet.png',
        ];

        $response = $this->apiAs(User::find(1), 'POST', "{$this->apiV1Base}/pets", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'data',
                'status',
                'message',
                'errors' => ['species']
            ]
        );
    }

    #[Test]
    public function breed_most_be_a_string(): void
    {
        $data = [
            'name' => 'New name',
            'species' => 'New species',
            'breed' => 1234,
            'age' => 2,
            'biography' => 'New Biography',
            'profile_picture' => 'photo-pet.png',
        ];

        $response = $this->apiAs(User::find(1), 'POST', "{$this->apiV1Base}/pets", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'data',
                'status',
                'message',
                'errors' => ['breed']
            ]
        );
    }

    #[Test]
    public function biography_most_be_a_string(): void
    {
        $data = [
            'name' => 'New name',
            'species' => 'New species',
            'breed' => 'New Breed',
            'age' => 2,
            'biography' => 1234,
            'profile_picture' => 'photo-pet.png',
        ];

        $response = $this->apiAs(User::find(1), 'POST', "{$this->apiV1Base}/pets", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'data',
                'status',
                'message',
                'errors' => ['biography']
            ]
        );
    }

    #[Test]
    public function profile_picture_most_be_a_string(): void
    {
        $data = [
            'name' => 'New name',
            'species' => 'New species',
            'breed' => 'New Breed',
            'age' => 2,
            'biography' => 'New Biography',
            'profile_picture' => 1234,
        ];

        $response = $this->apiAs(User::find(1), 'POST', "{$this->apiV1Base}/pets", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'data',
                'status',
                'message',
                'errors' => ['profile_picture']
            ]
        );
    }

    #[Test]
    public function age_picture_most_be_a_integer(): void
    {
        $data = [
            'name' => 'New name',
            'species' => 'New species',
            'breed' => 'New Breed',
            'age' => 'two',
            'biography' => 'New Biography',
            'profile_picture' => 'photo-pet.png',
        ];

        $response = $this->apiAs(User::find(1), 'POST', "{$this->apiV1Base}/pets", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(
            [
                'data',
                'status',
                'message',
                'errors' => ['age']
            ]
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }
}
