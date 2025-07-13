<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Author>
 */
final class AuthorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $firstName = fake()->firstName(),
            'last_name' => $lastName = fake()->lastName(),
            'bio' => fake()->paragraphs(3, asText: true),
            'website' => fake()->url(),
            'photo_url' => fake()->imageUrl(300, 400, 'people', randomize: true),
            'slug' => str("{$firstName} {$lastName}")->slug(),
        ];
    }
}
