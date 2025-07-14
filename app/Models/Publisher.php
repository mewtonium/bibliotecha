<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPublicUuid;
use App\Models\Concerns\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Publisher extends Model
{
    /** @use HasFactory<\Database\Factories\PublisherFactory> */
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
        'slug',
        'website',
        'logo_url',
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
     * Get the books for this publisher.
     */
    public function books(): HasMany
    {
        return $this->hasMany(Publisher::class);
    }
}
