<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPublicUuid;
use App\Models\Concerns\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Author extends Model
{
    /** @use HasFactory<\Database\Factories\AuthorFactory> */
    use HasFactory;
    use HasPublicUuid;
    use Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'slug',
        'bio',
        'website',
        'photo_url',
    ];

    /**
     * The attributes that are sluggable.
     *
     * @var array<string, string|string[]>
     */
    public function sluggable(): array
    {
        return [
            'slug' => ['first_name', 'last_name'],
        ];
    }

    /**
     * Get the books for this author.
     */
    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'books_authors');
    }
}
