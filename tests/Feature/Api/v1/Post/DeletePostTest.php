<?php

namespace Tests\Feature\Api\v1\Post;

use App\Models\Pet;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeletePostTest extends TestCase
{
    use RefreshDatabase;

    protected Pet $pet;
    protected Post $post;
    protected User $user;

    #[Test]
    public function an_authenticated_user_can_delete_their_posts(): void
    {
        $response = $this->apiAs($this->user, 'delete', "{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'OK',
        ]);
        $this->assertDatabaseMissing('posts', ['id' => $this->post->id]);
    }

    #[Test]
    public function a_unauthenticated_user_cannot_delete_any_posts(): void
    {
        $response = $this->deleteJson("{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}");

        $response->assertStatus(401);
    }

    #[Test]
    public function an_authenticated_user_can_only_delete_their_posts(): void
    {
        $user = User::factory()->create();

        $response = $this->apiAs($user, 'delete', "{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}");

        $response->assertStatus(403);
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->pet  = Pet::factory()->create(['user_id' => $this->user->id]);
        $this->post = Post::factory()->create(['pet_id' => $this->pet->id]);
    }
}
