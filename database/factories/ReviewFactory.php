<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
final class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'book_id' => Book::factory(),
            'user_id' => User::factory(),
            'rating' => fake()->numberBetween(0, 5),
            'status' => $status = fake()->randomElement(['pending', 'approved', 'rejected']),
            'spoiler' => fake()->boolean(25),
            'title' => fake()->sentence(),
            'body' => fake()->paragraphs(3, asText: true),
            'approved_at' => $status === 'approved' ? fake()->dateTimeBetween('-2 years') : null,
        ];
    }

    /**
     * Indicate that the review should be marked as pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'approved_at' => null,
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the review should be marked as rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'approved_at' => null,
            'status' => 'rejected',
        ]);
    }
}
