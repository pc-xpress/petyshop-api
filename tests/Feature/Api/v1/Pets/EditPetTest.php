<?php

namespace Tests\Feature\Api\v1\Pets;

use App\Models\Pet;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditPetTest extends TestCase
{
    use RefreshDatabase;

    private Pet $pet;

    #[Test]
    public function an_authenticated_user_can_edit_a_pet(): void
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

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}", $data);

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

        $this->assertDatabaseMissing('pets', [
            'id' => 1,
            'user_id' => 1,
            'name'    => 'Pet',
            'species' => 'Species',
            'breed' => 'Breed',
        ]);
    }

    #[Test]
    public function a_unauthenticated_user_cannot_edit_a_pet(): void
    {
        $data = [
            'name' => 'New Pet',
            'species' => 'New species',
            'breed' => 'New Breed',
            'age' => 2,
            'biography' => 'New Biography',
            'profile_picture' => 'photo-pet.png',
        ];

        $response = $this->putJson("{$this->apiV1Base}/pets/{$this->pet->id}", $data);

        $response->assertStatus(403);
    }

    #[Test]
    public function a_user_only_updates_their_pets(): void
    {
        $pet =  Pet::factory()->create();

        $data = [
            'name' => 'New Pet',
            'species' => 'New species',
            'breed' => 'New Breed',
            'age' => 2,
            'biography' => 'New Biography',
            'profile_picture' => 'photo-pet.png',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$pet->id}", $data);

        $response->assertStatus(403);
    }

    #[Test]
    public function the_slug_must_not_be_changed_if_name_is_the_same(): void
    {
        // $this->withoutExceptionHandling();
        $data = [
            'name' => 'Pet',
            'species' => 'New species',
            'breed' => 'New Breed',
            'age' => 2,
            'biography' => 'New Biography',
            'profile_picture' => 'photo-pet.png',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}", $data);

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

        $pet = Pet::find(1);
        $this->assertTrue($pet->slug === $this->pet->slug);

        $this->assertDatabaseMissing('pets', [
            'id' => 1,
            'user_id' => 1,
            'name'    => 'Pet',
            'species' => 'Species',
            'breed' => 'Breed',
        ]);
    }

    #[Test]
    public function name_most_be_required(): void
    {
        $data = [
            'name' => '',
            'species' => 'New species',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}", $data);

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

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}", $data);

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

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}", $data);

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

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}", $data);

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

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}", $data);

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

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}", $data);

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

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}", $data);

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

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}", $data);

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
        $this->pet = Pet::factory()->create([
            'user_id' => 1,
            'name'    => 'Pet',
            'slug'    => 'Pet',
            'species' => 'Species',
            'breed' => 'Breed',
        ]);
    }
}
