<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Publisher;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(10)->create();
        $authors = Author::factory(15)->create();
        $publishers = Publisher::factory(5)->create();

        $genres = Genre::factory()->createMany([
            ['name' => 'Fantasy'],
            ['name' => 'Science Fiction'],
            ['name' => 'Romance'],
            ['name' => 'Thriller'],
            ['name' => 'Non-Fiction'],
        ]);

        $books = Book::factory(50)
            ->create()
            ->each(function ($book) use ($authors, $genres, $publishers): void {
                $book->publisher()->associate($publishers->random())->save();

                $book->authors()->attach($authors->random(rand(1, 2))->pluck('id'));
                $book->genres()->attach($genres->random(rand(1, 3))->pluck('id'));
            });

        // The `reviews` table has a unique constraint on (book_id, user_id) to prevent duplicate
        // reviews by the same user for the same book. Using `recycle` doesn't respect
        // that constraint, so `crossJoin` is used to generate all valid user-book
        // pairs and randomly select a subset to seed without duplicates.
        $books
            ->crossJoin($users)
            ->shuffle()
            ->take(100)
            ->each(function (array $pair): void {
                [$book, $user] = $pair;

                Review::factory()->create([
                    'book_id' => $book->id,
                    'user_id' => $user->id,
                ]);
            });
    }
}
