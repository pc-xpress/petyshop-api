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
            'description'       => 'test description',
            'location'          => 'test location',
            'hide_like_view'    => false,
            'allow_commenting'  => false,
            'type'              => 'post',
            'visibility'        => 'public',
            'image'             => 'test image',
        ];

        $response = $this->apiAs(User::find(1), 'POST', "{$this->apiV1Base}/pets/{$this->pet->id}/posts", $data);

        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    'post' => [
                        'id', 'pet_id', 'pet_name', 'description', 'location', 'hide_like_view', 'allow_commenting', 'type', 'image'
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
                    'id'                => 1,
                    'pet_id'            => $this->pet->id,
                    'pet_name'          => $this->pet->name,
                    'image'             => $post->image,
                    'hide_like_view'    => false,
                    'allow_commenting'  => false,
                    'type'              => 'post',
                    'visibility'        => 'public',


                ]

            ]
        ]);
        $this->assertDatabaseHas('posts', [
            'id'                => 1,
            'pet_id'            => $this->pet->id,
            'description'       => 'test description',
            'location'          => 'test location',
            'hide_like_view'    => 0,
            'allow_commenting'  => 0,
            'type'              => 'post',
            'visibility'        => 'public',
            'image'             => $post->image,
        ]);
    }

    #[Test]
    public function a_unauthenticated_user_cannot_create_a_post(): void
    {
        $data = [
            'description'       => 'test description',
            'location'          => 'test location',
            'hide_like_view'    => true,
            'allow_commenting'  => false,
            'type'              => 'post',
            'visibility'        => 'public',
            'image'             => 'test image',
        ];

        $response = $this->postJson("{$this->apiV1Base}/pets/{$this->pet->id}/posts", $data);

        $response->assertStatus(401);
    }

    #[Test]
    public function a_authenticated_user_can_only_update_their_post(): void
    {
        $data = [
            'description'       => 'test description',
            'location'          => 'test location',
            'hide_like_view'    => false,
            'allow_commenting'  => false,
            'type'              => 'post',
            'visibility'        => 'public',
            'image'             => 'test image',
        ];
        $user = User::factory()->create();

        $response = $this->apiAs($user, 'POST', "{$this->apiV1Base}/pets/{$this->pet->id}/posts", $data);

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

        $response = $this->apiAs(User::find(1), 'POST', "{$this->apiV1Base}/pets/{$this->pet->id}/posts", $data);

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

        $response = $this->apiAs(User::find(1), 'POST', "{$this->apiV1Base}/pets/{$this->pet->id}/posts", $data);

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

        $response = $this->apiAs(User::find(1), 'POST', "{$this->apiV1Base}/pets/{$this->pet->id}/posts", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors' => ['hide_like_view']]);
    }

    #[test]
    public function post_allow_commenting_is_required(): void
    {
        $data = [
            'description'       => 'test description',
            'location'          => 'test location',
            'hide_like_view'   => false,
            'allow_commenting'  => '',
            'type'              => 'post',
            'visibility'        => 'public',
            'image'             => 'test image',
        ];

        $response = $this->apiAs(User::find(1), 'POST', "{$this->apiV1Base}/pets/{$this->pet->id}/posts", $data);

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

        $response = $this->apiAs(User::find(1), 'POST', "{$this->apiV1Base}/pets/{$this->pet->id}/posts", $data);

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

        $response = $this->apiAs(User::find(1), 'POST', "{$this->apiV1Base}/pets/{$this->pet->id}/posts", $data);

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

        $response = $this->apiAs(User::find(1), 'POST', "{$this->apiV1Base}/pets/{$this->pet->id}/posts", $data);

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

        $response = $this->apiAs(User::find(1), 'POST', "{$this->apiV1Base}/pets/{$this->pet->id}/posts", $data);

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

        $response = $this->apiAs(User::find(1), 'POST', "{$this->apiV1Base}/pets/{$this->pet->id}/posts", $data);

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

        $response = $this->apiAs(User::find(1), 'POST', "{$this->apiV1Base}/pets/{$this->pet->id}/posts", $data);

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

        $response = $this->apiAs(User::find(1), 'POST', "{$this->apiV1Base}/pets/{$this->pet->id}/posts", $data);

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

        $response = $this->apiAs(User::find(1), 'POST', "{$this->apiV1Base}/pets/{$this->pet->id}/posts", $data);

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
    }
}
