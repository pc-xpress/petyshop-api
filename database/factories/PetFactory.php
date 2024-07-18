<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->words(3, true);
        return [
            'user_id'     => fn () => User::factory()->create(),
            'name'        => $title,
            'slug'        => str($title)->slug(),
            'name' => fake()->word(3, true),
            'species' => fake()->word(2, true),
            'breed' => fake()->word(2, true),
            'age' => fake()->numberBetween(1, 10),
            'biography' => fake()->word(5, true),
            'profile_picture' => 'photo-pet.png',
        ];
    }
}
