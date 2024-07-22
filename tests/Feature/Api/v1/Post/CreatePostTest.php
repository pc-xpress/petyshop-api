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

class CreatePostTest extends TestCase
{

    use RefreshDatabase;

    protected $pet;

    #[Test]
    public function an_authenticated_user_can_create_a_post(): void
    {
        $data = [
            'content' => 'test content',
            'image' => 'test image',
        ];
        $response = $this->apiAs(User::find(1), 'POST', "{$this->apiV1Base}/pets/{$this->pet->id}/posts", $data);
        // $response->dd();

        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    'post' => [
                        'id', 'pet_id', 'pet_name', 'content', 'image'
                    ]
                ],
                'message',
                'success',
                'status',
                'errors',
            ]
        );

        $post = Post::find(1);
        $response->assertJsonFragment([
            'data' => [
                'post' => [
                    ...$data,
                    'id' => 1,
                    'pet_id' => $this->pet->id,
                    'pet_name' => $this->pet->name,
                    'image' => $post->image,
                ]

            ]
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => 1,
            'pet_id' => $this->pet->id,
            'content' => $data['content'],
            'image' => $data['image'],
        ]);
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
