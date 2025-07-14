<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'bio' => fake()->paragraphs(3, asText: true),
            'website' => fake()->url(),
            'photo_url' => fake()->imageUrl(300, 400, 'people', randomize: true),
        ];
    }

    /**
     * Add the slug attribute to this model.
     */
    public function slug(): static
    {
        return $this->state(fn (array $attributes) => [
            'slug' => Str::slug("{$attributes['first_name']} {$attributes['last_name']}"),
        ]);
    }
}
