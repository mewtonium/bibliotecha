<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Publisher>
 */
final class PublisherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $name = fake()->company(),
            'website' => fake()->url(),
            'logo_url' => fake()->imageUrl(200, 100, 'business', randomize: true),
            'slug' => str($name)->slug(),
        ];
    }
}
