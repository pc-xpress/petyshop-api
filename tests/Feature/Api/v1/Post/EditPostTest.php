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
            'description'       => 'Update test description',
            'location'          => 'Update test location',
            'hide_like_view'    => false,
            'allow_commenting'  => false,
            'type'              => 'post',
            'visibility'        => 'public',
            'image'             => 'test image',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}", $data);

        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    'post' => [
                        'id', 'description', 'location', 'hide_like_view', 'allow_commenting', 'type', 'visibility', 'image',
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
                    'id'        => $this->post->id,
                    'pet_id'    => $this->pet->id,
                    'pet_name'  => $this->pet->name
                ]

            ]
        ]);

        $this->assertDatabaseHas('posts', [
            'id'                => $this->post->id,
            'description'       => $data['description'],
            'location'          => $data['location'],
            'hide_like_view'    => $data['hide_like_view'],
            'allow_commenting'  => $data['allow_commenting'],
            'type'              => $data['type'],
            'visibility'        => $data['visibility'],
            'image'             => $data['image'],
        ]);
    }

    #[Test]
    public function a_unauthenticated_user_cannot_update_a_post(): void
    {
        $data = [
            'description'       => 'Update test description',
            'location'          => 'Update test location',
            'hide_like_view'    => false,
            'allow_commenting'  => false,
            'type'              => 'post',
            'visibility'        => 'public',
            'image'             => 'test image',
        ];

        $response = $this->putJson("{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}", $data);

        $response->assertStatus(401);
    }

    #[Test]
    public function a_user_authenticated_can_only_edit_their_posts(): void
    {
        $data = [
            'description'       => 'Update test description',
            'location'          => 'Update test location',
            'hide_like_view'    => false,
            'allow_commenting'  => false,
            'type'              => 'post',
            'visibility'        => 'public',
            'image'             => 'test image',
        ];
        $user = User::factory()->create();

        $response = $this->apiAs($user, 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}", $data);

        $response->assertStatus(403);
    }



    #[test]
    public function post_hide_like_view_is_required(): void
    {
        $data = [
            'description'       => 'test description',
            'location'          => 'test location',
            'hide_like_view'    => '',
            'allow_commenting'  => false,
            'type'              => 'post',
            'visibility'        => 'public',
            'image'             => 'test image',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors' => ['hide_like_view']]);
    }

    #[test]
    public function post_hide_like_view_only_be_true_or_false(): void
    {
        $data = [
            'description'       => 'test description',
            'location'          => 'test location',
            'hide_like_view'    => 'string',
            'allow_commenting'  => false,
            'type'              => 'post',
            'visibility'        => 'public',
            'image'             => 'test image',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors' => ['hide_like_view']]);
    }

    #[test]
    public function post_hide_like_view_cannot_be_null(): void
    {
        $data = [
            'description'       => 'test description',
            'location'          => 'test location',
            'hide_like_view'    => null,
            'allow_commenting'  => false,
            'type'              => 'post',
            'visibility'        => 'public',
            'image'             => 'test image',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors' => ['hide_like_view']]);
    }

    #[test]
    public function post_allow_commenting_is_required(): void
    {
        $data = [
            'description'       => 'test description',
            'location'          => 'test location',
            'hide_like_view'    => false,
            'allow_commenting'  => '',
            'type'              => 'post',
            'visibility'        => 'public',
            'image'             => 'test image',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors' => ['allow_commenting']]);
    }

    #[test]
    public function post_allow_commenting_only_be_true_or_false(): void
    {
        $data = [
            'description'       => 'test description',
            'location'          => 'test location',
            'hide_like_view'    => false,
            'allow_commenting'  => 'string',
            'type'              => 'post',
            'visibility'        => 'public',
            'image'             => 'test image',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors' => ['allow_commenting']]);
    }

    #[test]
    public function post_allow_commenting_cannot_be_null(): void
    {
        $data = [
            'description'       => 'test description',
            'location'          => 'test location',
            'hide_like_view'    => false,
            'allow_commenting'  => null,
            'type'              => 'post',
            'visibility'        => 'public',
            'image'             => 'test image',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors' => ['allow_commenting']]);
    }

    #[test]
    public function post_type_is_required(): void
    {
        $data = [
            'description'       => 'test description',
            'location'          => 'test location',
            'hide_like_view'    => false,
            'allow_commenting'  => false,
            'type'              => '',
            'visibility'        => 'public',
            'image'             => 'test image',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors' => ['type']]);
    }

    #[test]
    public function post_type_only_be_post_or_reel(): void
    {
        $data = [
            'description'       => 'test description',
            'location'          => 'test location',
            'hide_like_view'    => false,
            'allow_commenting'  => false,
            'type'              => 'null',
            'visibility'        => 'public',
            'image'             => 'test image',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors' => ['type']]);
    }

    #[test]
    public function post_type_cannot_be_null(): void
    {
        $data = [
            'description'       => 'test description',
            'location'          => 'test location',
            'hide_like_view'    => false,
            'allow_commenting'  => false,
            'type'              => null,
            'visibility'        => 'public',
            'image'             => 'test image',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors' => ['type']]);
    }

    #[test]
    public function post_visibility_is_required(): void
    {
        $data = [
            'description'       => 'test description',
            'location'          => 'test location',
            'hide_like_view'    => false,
            'allow_commenting'  => false,
            'type'              => 'post',
            'visibility'        => '',
            'image'             => 'test image',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors' => ['visibility']]);
    }

    #[test]
    public function post_visibility_only_be_public_or_private(): void
    {
        $data = [
            'description'       => 'test description',
            'location'          => 'test location',
            'hide_like_view'    => false,
            'allow_commenting'  => false,
            'type'              => 'reel',
            'visibility'        => 'null',
            'image'             => 'test image',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors' => ['visibility']]);
    }

    #[test]
    public function post_visibility_cannot_be_null(): void
    {
        $data = [
            'description'       => 'test description',
            'location'          => 'test location',
            'hide_like_view'    => false,
            'allow_commenting'  => false,
            'type'              => 'reel',
            'visibility'        => null,
            'image'             => 'test image',
        ];

        $response = $this->apiAs(User::find(1), 'PUT', "{$this->apiV1Base}/pets/{$this->pet->id}/posts/{$this->post->id}", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors' => ['visibility']]);
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
