<?php

namespace Tests\Feature\Api\v1\Pets;

use App\Models\Pet;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PetListTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_unauthenticated_user_must_see_their_pets(): void
    {
        // $this->withoutExceptionHandling();
        $response = $this->apiAs(User::find(1), 'GET', "{$this->apiV1Base}/pets");
        // $response->dd();
        $response->assertJsonCount(15, 'data.pets');
        $response->assertStatus(200);
    }

    #[Test]
    public function a_unauthenticated_user_cannot_edit_a_pet(): void
    {
        $response = $this->getJson("{$this->apiV1Base}/pets");

        $response->assertStatus(401);
    }

    #[Test]
    public function an_authenticated_user_must_see_only_their_pets(): void
    {
        $user = User::factory()->create();
        $response = $this->apiAs($user, 'GET', "{$this->apiV1Base}/pets");
        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data.pets');
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
        $this->pets = Pet::factory()->count(15)->create([
            'pet_id' => 1,
        ]);
    }
}
