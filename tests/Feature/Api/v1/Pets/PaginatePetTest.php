<?php

namespace Tests\Feature\Api\v1\Pets;

use App\Models\Pet;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaginatePetTest extends TestCase
{
    use RefreshDatabase;

    protected $pets;

    #[Test]
    public function a_user_can_see_their_pets_with_pagination(): void
    {
        $this->withoutExceptionHandling();
        $response = $this->apiAs(User::find(1), 'GET', "{$this->apiV1Base}/pets");
        $response->assertStatus(200);

        $response->assertJsonCount(15, 'data.pets');
        $response->assertJsonStructure(['status', 'success', 'errors', 'message', 'data' => [
            'pets',
            'total',
            'count',
            'per_page',
            'current_page',
            'total_pages',
        ]]);

        $response->assertJsonPath('data.total', 150);
        $response->assertJsonPath('data.current_page', 1);
        $response->assertJsonPath('data.per_page', 15);
        $response->assertJsonPath('data.total_pages', 10);
        $response->assertJsonPath('data.count', 15);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
        $this->pets = Pet::factory()->count(150)->create([
            'user_id' => 1,
        ]);
    }
}
