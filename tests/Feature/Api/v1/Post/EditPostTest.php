<?php

namespace Tests\Feature\Api\v1\Post;

use App\Models\Pet;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\Concerns\WithoutExceptionHandlingHandler;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditPostTest extends TestCase
{

    use RefreshDatabase;

    protected Pet $pet;
    protected Post $post;

    #[Test]
    public function an_authenticated_user_can_edit_a_post(): void
    {
        $data = [
            'content' => 'New test content',
            'image' => 'New test image',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}", $data);

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
        $response->assertJsonFragment([
            'data' => [
                'post' => [
                    ...$data,
                    'id' => $this->post->id,
                    'pet_id' => $this->pet->id,
                    'pet_name' => $this->pet->name
                ]

            ]
        ]);
        $this->assertDatabaseMissing('posts', [
            'content' => 'test content',
            'image' => 'test image',
        ]);
    }

    #[Test]
    public function a_unauthenticated_user_cannot_update_a_post(): void
    {
        $data = [
            'content' => 'New test content',
            'image' => 'New test image',
        ];

        $response = $this->putJson("{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}", $data);

        $response->assertStatus(401);
    }

    #[Test]
    public function a_user_authenticated_can_only_edit_their_posts(): void
    {
        $data = [
            'content' => 'New test content',
            'image' => 'New test image',
        ];
        $user = User::factory()->create();

        $response = $this->apiAs($user, 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}", $data);

        $response->assertStatus(403);
    }



    #[test]
    public function post_content_is_required(): void
    {
        $data = [
            'content' => '',
            'image' => 'New test image',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors' => ['content']]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
        $this->pet = Pet::factory()->create([
            'user_id' => 1,
        ]);
        $this->post = Post::factory()->create([
            'pet_id' => $this->pet->id,
        ]);
    }
}
