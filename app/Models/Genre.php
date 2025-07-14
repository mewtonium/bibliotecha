<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPublicUuid;
use App\Models\Concerns\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Genre extends Model
{
    /** @use HasFactory<\Database\Factories\GenreFactory> */
    use HasFactory;
    use HasPublicUuid;
    use Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'slug',
    ];

    /**
     * The attributes that are sluggable.
     *
     * @var list<string, string|list<string>>
     */
    public function sluggable(): array
    {
        return [
            'slug' => 'name',
        ];
    }

    /**
     * Get the books for this genre.
     */
    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'books_genres');
    }
}
