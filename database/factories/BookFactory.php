<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
final class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'publisher_id' => Publisher::factory(),
            'title' => $title = fake()->catchPhrase(),
            'subtitle' => fake()->sentence(),
            'synopsis' => fake()->paragraphs(4, true),
            'isbn' => fake()->isbn13(),
            'language' => 'en',
            'page_count' => fake()->numberBetween(100, 600),
            'format' => fake()->randomElement(['Paperback', 'Hardcover', 'Ebook']),
            'cover_url' => fake()->imageUrl(400, 600, 'books', randomize: true),
            'released_at' => fake()->date(),
            'slug' => str($title)->slug(),
        ];
    }
}
