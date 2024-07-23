<?php

namespace Tests\Feature\Api\v1\Post;

use App\Models\Pet;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Database\Seeders\UserSeeder;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostlistTest extends TestCase
{
    use RefreshDatabase;

    protected $pet;
    protected $posts;

    #[Test]
    public function a_unauthenticated_user_must_see_their_posts(): void
    {
        $response = $this->apiAs(User::find(1), 'GET', "{$this->apiV1Base}/pets/{$this->pet->id}/posts");

        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data.posts');
        $response->assertJsonStructure([
            'data' => [
                'posts' => [
                    '*' => [
                        'id',
                        'pet_id',
                        'pet_name',
                        'content',
                        'image',
                    ],
                ],
            ],
        ]);
        $response->assertJsonPath('data.posts.0.pet_id', $this->pet->id);
    }

    #[Test]
    public function a_user_can_see_their_posts_with_pagination(): void
    {
        $response = $this->apiAs(User::find(1), 'GET', "{$this->apiV1Base}/pets/{$this->pet->id}/posts");

        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data.posts');
        $response->assertJsonStructure(['status', 'success', 'errors', 'message', 'data' => [
            'posts',
            'total',
            'count',
            'per_page',
            'current_page',
            'total_pages',
        ]]);

        $response->assertJsonPath('data.total', 15);
        $response->assertJsonPath('data.current_page', 1);
        $response->assertJsonPath('data.per_page', 15);
        $response->assertJsonPath('data.total_pages', 1);
        $response->assertJsonPath('data.count', 15);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
        $this->pet = Pet::factory()->create([
            'user_id' => 1,
        ]);
        $this->posts = Post::factory()->count(15)->create([
            'pet_id' => $this->pet,
        ]);
    }
}
