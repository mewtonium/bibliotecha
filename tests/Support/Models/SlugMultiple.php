<?php

declare(strict_types=1);

namespace Tests\Support\Models;

use App\Models\Concerns\Sluggable;
use Illuminate\Database\Eloquent\Model;

final class SlugMultiple extends Model
{
    use Sluggable;

    protected $table = 'test_sluggable';

    protected $guarded = [];

    public function sluggable(): array
    {
        return [
            'slug' => 'title',
            'url_slug' => 'full_name',
        ];
    }
}
