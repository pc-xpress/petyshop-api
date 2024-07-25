<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\Post;
use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Api\v1\Post\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pet_id'            => fn () => Pet::factory()->create()->id,
            'description'       => fake()->sentence,
            'location'          => fake()->city,
            'hide_like_view'    => fake()->boolean,
            'allow_commenting'  => fake()->boolean,
            'type'              => fake()->randomElement(['post', 'reel']),
            'visibility'        => fake()->randomElement(['public', 'private']),
            'image'             => fake()->imageUrl,
        ];
    }

    function configure()
    {
        return $this->afterCreating(function (Post $post) {
            if ($post->type == 'reel') {
                Media::factory()->reel()->create(['mediable_type' => get_class($post), 'mediable_id' => $post->id]);
            } else {
                Media::factory()->post()->create(['mediable_type' => get_class($post), 'mediable_id' => $post->id]);
            }
        });
    }
}
