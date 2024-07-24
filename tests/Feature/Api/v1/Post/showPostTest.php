<?php

namespace Tests\Feature\Api\v1\Post;

use App\Models\Pet;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowPostTest extends TestCase
{
    use RefreshDatabase;

    protected Pet $pet;
    protected Post $post;
    protected User $user;

    #[Test]
    public function an_authenticated_user_can_see_their_posts(): void
    {
        $response = $this->apiAs($this->user, 'GET', "{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'post' => [
                    'id',
                    'pet_id',
                    'pet_name',
                    'description',
                    'location',
                    'hide_like_view',
                    'allow_commenting',
                    'type',
                    'visibility',
                    'image',

                ],
            ],
        ]);
        $response->assertJsonFragment([
            'data' => [
                'post' => [
                    'id'                => $this->post->id,
                    'pet_id'            => $this->post->pet_id,
                    'pet_name'          => $this->post->pet->name,
                    'description'       => $this->post->description,
                    'location'          => $this->post->location,
                    'hide_like_view'    => $this->post->hide_like_view,
                    'allow_commenting'  => $this->post->allow_commenting,
                    'type'              => $this->post->type,
                    'visibility'        => $this->post->visibility,
                    'image'             => $this->post->image,
                ],
            ],
        ]);
    }

    #[Test]
    public function a_unauthenticated_user_cannot_see_any_posts(): void
    {
        $response = $this->getJson("{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}");

        $response->assertStatus(401);
    }

    #[Test]
    public function an_authenticated_user_can_only_see_their_posts(): void
    {
        $user = User::factory()->create();

        $response = $this->apiAs($user, 'GET', "{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}");

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
